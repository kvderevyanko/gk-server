(function ($) {
    var charts = {};

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
        $card.find('[data-dht-status]').text(message);
    }

    function showReadings($card, response) {
        var $readings = $card.find('[data-dht-readings]').empty();
        var temperature = response.temperature;
        var humidity = response.humidity;

        if (temperature !== undefined && temperature !== null && temperature !== '') {
            $('<span><strong></strong>Температура</span>').find('strong').text(temperature + '°').end().appendTo($readings);
        }
        if (humidity !== undefined && humidity !== null && humidity !== '') {
            $('<span><strong></strong>Влажность</span>').find('strong').text(humidity + '%').end().appendTo($readings);
        }
        if (!$readings.children().length) {
            $('<p>').text('Устройство не вернуло измерение.').appendTo($readings);
        }

        $card.find('[data-dht-time]').text('Только что получено и сохранено');
    }

    function updateChart(chart, rows) {
        chart.data = {
            labels: rows.map(function (row) { return row.dateTime; }),
            datasets: [
                {
                    label: 'Температура, °C',
                    data: rows.map(function (row) { return row.temperature; }),
                    borderColor: '#3159d7',
                    backgroundColor: 'rgba(49, 89, 215, .12)',
                    fill: true,
                    tension: .3
                },
                {
                    label: 'Влажность, %',
                    data: rows.map(function (row) { return row.humidity; }),
                    borderColor: '#16794a',
                    backgroundColor: 'rgba(22, 121, 74, .08)',
                    fill: true,
                    tension: .3
                }
            ]
        };
        chart.update();
    }

    $(document).on('click', '.dht-update', function () {
        var $button = $(this);
        var $card = $button.closest('[data-dht-card]');
        var $panel = $button.closest('[data-dht-panel]');
        var data = csrfData({ deviceId: $button.data('device'), pin: $button.data('pin') });

        $button.prop('disabled', true);
        feedback($card, 'pending', 'Запрашиваем показания у устройства…');
        var requestToken = window.beginDeviceRequest({
            action: 'Получаем показания DHT',
            device: $card.find('.control-card__device').text()
        });

        $.ajax({
            url: $panel.data('command-url'),
            method: 'POST',
            data: data,
            dataType: 'json'
        }).done(function (response) {
            if (response && response.status === 'ok') {
                showReadings($card, response);
                feedback($card, 'confirmed', response.message || 'Показания получены и сохранены.');
                return;
            }

            feedback($card, 'error', (response && response.message) || 'Устройство не подтвердило получение показаний.');
        }).fail(function (xhr) {
            feedback($card, 'error', (xhr.responseJSON || {}).message || 'Не удалось связаться с устройством. Показания не обновлены.');
        }).always(function () {
            $button.prop('disabled', false);
            window.endDeviceRequest(requestToken);
        });
    });

    $(document).on('click', '.dht-graph', function () {
        var $button = $(this);
        var $card = $button.closest('[data-dht-card]');
        var $panel = $button.closest('[data-dht-panel]');
        var $chartBox = $('#' + $button.attr('aria-controls'));
        var chartKey = $button.data('device') + '_' + $button.data('pin');
        var chart;

        $button.prop('disabled', true);
        feedback($card, 'pending', 'Загружаем историю измерений…');

        $.getJSON($panel.data('graph-url'), { deviceId: $button.data('device'), pin: $button.data('pin') })
            .done(function (rows) {
                $chartBox.prop('hidden', false);
                $button.attr('aria-expanded', 'true');
                chart = charts[chartKey];
                if (!chart) {
                    chart = new Chart($chartBox.find('[data-dht-chart]')[0], { type: 'line', data: {} });
                    charts[chartKey] = chart;
                }
                updateChart(chart, rows);
                feedback($card, 'confirmed', rows.length ? 'История измерений загружена.' : 'В истории пока нет измерений.');
            }).fail(function () {
                feedback($card, 'error', 'Не удалось загрузить историю измерений.');
            }).always(function () {
                $button.prop('disabled', false);
            });
    });
}(jQuery));
