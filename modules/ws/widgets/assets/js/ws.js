(function ($) {
    function csrfData(data) {
        var param = $('meta[name="csrf-param"]').attr('content');
        var token = $('meta[name="csrf-token"]').attr('content');

        if (param && token) {
            data[param] = token;
        }

        return data;
    }

    function feedback($card, state, message) {
        $card.removeClass('is-pending is-confirmed is-error').addClass('is-' + state);
        $card.find('[data-ws-status]').text(message);
    }

    function command($card, $panel, deviceId) {
        var data = { deviceId: deviceId };
        $card.find('[data-ws-field]').each(function () {
            data[this.name] = $(this).val();
        });
        data = csrfData(data);

        $card.find('[data-ws-field]').prop('disabled', true);
        feedback($card, 'pending', 'Параметры отправляются на устройство…');

        $.ajax({
            url: $panel.data('command-url'),
            method: 'POST',
            data: data,
            dataType: 'json'
        }).done(function (response) {
            if (response && response.status === 'ok') {
                feedback($card, 'confirmed', response.message || 'Параметры подтверждены устройством.');
                return;
            }

            feedback($card, 'error', (response && response.message) || 'Устройство не подтвердило параметры WS2812.');
        }).fail(function (xhr) {
            feedback($card, 'error', (xhr.responseJSON || {}).message || 'Не удалось связаться с устройством. Параметры сохранены, но не подтверждены.');
        }).always(function () {
            $card.find('[data-ws-field]').prop('disabled', false);
        });
    }

    $(document).on('input', '.ws-control[type="range"]', function () {
        $(this).closest('.ws-field__range').find('[data-ws-output]').text(this.value);
    });

    $(document).on('change', '.ws-control', function () {
        var $control = $(this);
        var $card = $control.closest('[data-ws-card]');
        var $panel = $control.closest('[data-ws-panel]');

        command($card, $panel, $card.data('device'));
    });
}(jQuery));
