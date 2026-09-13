/**
 * A single, non-technical indicator for in-flight physical commands. Multiple
 * cards may be waiting at once; the most recent command stays visible until
 * it completes, then the previous one is restored if necessary.
 */
(function ($) {
    var activeRequests = {};
    var requestSequence = 0;

    function renderRequestToast() {
        var tokens = Object.keys(activeRequests);
        var $toast = $('#waitRequest');

        if (!tokens.length) {
            $toast.prop('hidden', true);
            return;
        }

        var request = activeRequests[tokens[tokens.length - 1]];
        $toast.find('[data-request-title]').text(request.action || 'Команда отправляется');
        $toast.find('[data-request-message]').text(request.device
            ? 'Устройство «' + request.device + '»: ожидаем подтверждение ESP.'
            : 'Ожидаем подтверждение устройства.');
        $toast.prop('hidden', false);
    }

    window.beginDeviceRequest = function (options) {
        requestSequence += 1;
        var token = 'device-request-' + requestSequence;
        activeRequests[token] = options || {};
        renderRequestToast();

        return token;
    };

    window.endDeviceRequest = function (token) {
        delete activeRequests[token];
        renderRequestToast();
    };
}(jQuery));

function updateSortable(div){
    div.sortable();
    div.disableSelection();
}

/**
 * The GPIO card intentionally does not retry a physical command on its own:
 * repeated requests can be unsafe for relays and motors. The UI instead keeps
 * the desired value visible and tells the operator when delivery is unknown.
 */
(function ($) {
    function setGpioFeedback($card, $control, state, message) {
        var $status = $card.find('[data-gpio-status]');
        var $state = $card.find('[data-gpio-state]');

        $card.removeClass('is-pending is-confirmed is-error').addClass('is-' + state);
        $status.text(message);

        if (state === 'pending') {
            $state.text('Отправляем…');
        } else if (state === 'error') {
            $state.text('Не подтверждено');
        } else {
            $state.text($control.prop('checked') ? 'Включено' : 'Выключено');
        }
    }

    $(document).on('change', '.gpio-control', function () {
        var $control = $(this);
        var $card = $control.closest('[data-gpio-card]');
        var data = {
            deviceId: $control.data('device'),
            pin: $control.data('pin'),
            value: $control.prop('checked') ? 1 : 0
        };
        var csrfParam = $('meta[name="csrf-param"]').attr('content');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (csrfParam && csrfToken) {
            data[csrfParam] = csrfToken;
        }

        $control.prop('disabled', true);
        setGpioFeedback($card, $control, 'pending', 'Команда отправляется на устройство…');
        var requestToken = window.beginDeviceRequest({
            action: 'Изменяем переключатель',
            device: $card.find('.control-card__device').text()
        });

        $.ajax({
            url: $control.data('url'),
            method: 'POST',
            data: data,
            dataType: 'json'
        }).done(function (response) {
            if (response && response.status === 'ok') {
                setGpioFeedback($card, $control, 'confirmed', response.message || 'Команда подтверждена устройством.');
                return;
            }

            setGpioFeedback($card, $control, 'error', (response && response.message) || 'Устройство не подтвердило команду.');
        }).fail(function (xhr) {
            var response = xhr.responseJSON || {};
            setGpioFeedback($card, $control, 'error', response.message || 'Не удалось связаться с устройством. Состояние сохранено, но не подтверждено.');
        }).always(function () {
            $control.prop('disabled', false);
            window.endDeviceRequest(requestToken);
        });
    });

    $(document).on('click', '.app-menu-button', function () {
        var $button = $(this);
        var isOpen = !$('body').hasClass('navigation-open');

        $('body').toggleClass('navigation-open', isOpen);
        $button.attr('aria-expanded', isOpen ? 'true' : 'false');
    });

    $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && $('body').hasClass('navigation-open')) {
            $('body').removeClass('navigation-open');
            $('.app-menu-button').attr('aria-expanded', 'false').trigger('focus');
        }
    });
}(jQuery));

(function ($) {
    function feedback($card, state, message) { $card.removeClass('is-pending is-confirmed is-error').addClass('is-' + state); $card.find('[data-pwm-status]').text(message); }
    $(document).on('input', '.pwm-control', function () { $(this).closest('[data-pwm-card]').find('[data-pwm-value]').text(this.value); });
    $(document).on('change', '.pwm-control', function () {
        var $control = $(this), $card = $control.closest('[data-pwm-card]'), data = {deviceId: $control.data('device'), pin: $control.data('pin'), value: $control.val()};
        var param = $('meta[name="csrf-param"]').attr('content'), token = $('meta[name="csrf-token"]').attr('content'); if (param && token) data[param] = token;
        $control.prop('disabled', true); feedback($card, 'pending', 'Значение отправляется на устройство…');
        var requestToken = window.beginDeviceRequest({action: 'Изменяем значение PWM', device: $card.find('.control-card__device').text()});
        $.ajax({url: $control.data('url'), method: 'POST', data: data, dataType: 'json'}).done(function (response) { feedback($card, response && response.status === 'ok' ? 'confirmed' : 'error', (response && response.message) || 'Устройство не подтвердило значение PWM.'); }).fail(function (xhr) { feedback($card, 'error', (xhr.responseJSON || {}).message || 'Не удалось связаться с устройством. Значение не подтверждено.'); }).always(function () { $control.prop('disabled', false); window.endDeviceRequest(requestToken); });
    });
}(jQuery));
