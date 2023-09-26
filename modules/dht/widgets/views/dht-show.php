<?php

use app\modules\dht\models\Dht;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dhtList array */
/* @var $dht Dht */

?>


    <div class="col-sm-12">

        <h4>Температура</h4>
        <?php foreach ($dhtList as $dht): ?>
            <div class="row">
                <div class="col-md-4">
                    <div id="dht_block_<?= $dht->deviceId ?>_<?= $dht->pin ?>">
                        <label><?= $dht->name ?> / <?= $dht->device->name ?></label>
                        <br>
                        <span class="dhtInfo">
                            <?php
                            echo $this->render('_dht-show-temperature',
                                ['lastTemperature' => $dht->lastTemperature($dht->pin)])
                            ?>
                        </span>
                        <br> <br>
                        <span class="btn btn-info dht-button"
                              data-device="<?= $dht->deviceId ?>"
                              data-pin="<?= $dht->pin ?>"
                        >Обновить температуру</span><br>
                        <br>
                        <span class="btn btn-success dht-button"
                              data-device="<?= $dht->deviceId ?>"
                              data-pin="<?= $dht->pin ?>"
                        >Показать график</span><br>
                    </div>
                </div>
                <div class="col-md-8">
                    <canvas id="acquisitions">saxdas</canvas>
                </div>
            </div>


            <hr>
        <?php endforeach; ?>
    </div>
    <script>
      let urlCommandDht = "<?=Url::to(['/dht/request/get-temperature'])?>"
      let urlShowGraphDht = "<?=Url::to(['/dht/request/show-graph'])?>"
    </script>
<style>
    #acquisitions {
        max-height: 300px;
    }
</style>
<?php
$this->registerJs(<<<JS
  const data = [
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
  );



let blockDhtRequest
let waitDhtRequest

$('.dht-button').on('click', function() {
    commandDht($(this).data('device'), $(this).data('pin'))
})

let deviceRepeatDht = 0

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
      if(waitDhtRequest) {
        commandDht(waitDhtRequest)
      }
          
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

JS
);