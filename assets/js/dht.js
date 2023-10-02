/*const data = [
    { year: 2010, count: 10 },
    { year: 2011, count: 20 },
    { year: 2012, count: 15 },
    { year: 2013, count: 25 },
    { year: 2014, count: 22 },
    { year: 2015, count: 30 },
    { year: 2016, count: 28 },
  ];

  new Chart(
    document.getElementById('acquisitions'),
    {
      type: 'bar',
      data: {
        labels: data.map(row => row.year),
        datasets: [
          {
            label: 'Acquisitions by year',
            data: data.map(row => row.count)
          }
        ]
      }
    }
  );*/



let blockDhtRequest
let waitDhtRequest
let deviceRepeatDht = 0

let charts = {};

$('.dht-button').on('click', function() {
  commandDht($(this).data('device'), $(this).data('pin'))
})

$('.dht-graph').on('click', function() {
  graphDht($(this).data('device'), $(this).data('pin'))
})

function graphDht(deviceId, pin) {
  $.get(urlGetGraphInfo, {deviceId:deviceId, pin:pin}, function (data) {
    let chart;
    if(charts[deviceId+'_'+pin]) {
      chart = charts[deviceId+'_'+pin];
    } else {
      chart = new Chart( document.getElementById('acquisitions_'+deviceId+'_'+pin), {
        type: 'bar',
        data:{}
      });
    }
    updateChart(chart, data)
  })
}


function updateChart(chart, data){
  chart.data = {
    labels: data.map(row => row.dateTime),
    datasets: [
      {
        label: 'Температура',
        data: data.map(row => row.temperature)
      }
    ]
  };
  chart.update();
}

/**
 * Отправка команды для запроса на термометр
 * @param deviceId
 * @param pin
 * @returns {boolean}
 */
function commandDht(deviceId, pin) {

  if(deviceRepeatDht === 0)
    deviceRepeatDht = 1

  if(blockDhtRequest) {
    waitDhtRequest = deviceId
    return false
  }
  blockDhtRequest = true
  waitDhtRequest = false

  let dhtInfo = $('#dht_block_'+deviceId+'_'+pin).find('.dhtInfo')
  dhtInfo.text('Получаем данные')
  let request = {deviceId:deviceId, pin:pin}
  openWaitRequest(deviceId, deviceRepeatDht)
  $.get(urlCommandDht, request, function(data) {
    blockDhtRequest = false
    /*if(waitDhtRequest) {
      commandDht(waitDhtRequest,pin)
    }*/
    data = JSON.parse(data)
    if(data['status'] === 'ok') {
      hideWaitRequest(deviceId, deviceRepeatDht);
      let text = ''
      if(data['temperature'])
        text+=' Температура: '+data['temperature']
      if(data['humidity'])
        text+=' Влажность: '+data['humidity']

      dhtInfo.text(text)
    } else {
      alert( 'Ошибка устройства '+data['message'] );
      hideWaitRequest(deviceId, deviceRepeatDht);
    }
  }).fail(function() {
    blockDhtRequest = false
    waitDhtRequest = false

    if(deviceRepeatDht < 5) {
      deviceRepeatDht++
      commandDht(deviceId, deviceRepeatDht)
    } else {
      hideWaitRequest(deviceId, deviceRepeatDht)
      alert( 'Ошибка отправки запроса после 5 попыток' )
      deviceRepeatDht = 0
    }
  })
}