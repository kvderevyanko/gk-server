function cl(str) {
    console.log(str);
}

let waitRequest = $("#waitRequest");
let waitRequestId = $("#waitRequestId");
let requestRepeat = $("#requestRepeat");
function openWaitRequest(id, repeat, message) {
    //wait_request.show();
    if(repeat) {
        requestRepeat.show();
        requestRepeat.find('span').text(repeat);
    } else {
        requestRepeat.hide();
    }
    waitRequestId.text(id);
    waitRequest.slideDown();
}

function hideWaitRequest(id, repeat, message) {
    if(repeat) {
        requestRepeat.show();
        requestRepeat.find('span').text(repeat);
    } else {
        requestRepeat.hide();
    }
    waitRequestId.text(id);
    waitRequest.slideUp();
}

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
