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
            <div id="dht_block_<?= $dht->deviceId ?>_<?= $dht->pin ?>">
                <label><?= $dht->name ?> / <?= $dht->device->name ?></label>
                <br>
                <span class="btn btn-info dht-button"
                      data-device="<?= $dht->deviceId ?>"
                      data-pin="<?= $dht->pin ?>"
                >Показать температуру</span><br>
                <span class="dhtInfo">
            <?php
            echo $this->render('_dht-show-temperature',
                ['lastTemperature' => $dht->lastTemperature($dht->pin)])
            //$lastTemperature = $dht->lastTemperature($dht->pin);
            /*if ($lastTemperature) {
                echo date('d/m H:i:s', $lastTemperature->datetime);
                if ($lastTemperature->temperature)
                    echo ' Температура: ' . $lastTemperature->temperature . ', ';

                if ($lastTemperature->humidity)
                    echo ' Влажность: ' . $lastTemperature->humidity;
            }*/
            ?>
        </span>

            </div>
            <hr>
        <?php endforeach; ?>
    </div>
    <script>
        let urlCommandDht = "<?=Url::to(['/dht/dht/request'])?>";
    </script>
<?php
$this->registerJs(<<<JS

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