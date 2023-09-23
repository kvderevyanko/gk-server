<?php

use app\modules\gpio\models\Gpio;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $gpioValues array */
/* @var $gpio Gpio */


?>
    <div class="col-sm-6">
<h4>GPIO</h4>
<div id="gpioBlock">
<?php foreach ($gpioValues as $gpio): ?>
<label  class="checkBoxSwitch">

    <?= Html::checkbox('', ($gpio->value?$gpio->value:0), [
        'data-pin'=>$gpio->pin,
        'data-device'=>$gpio->deviceId,
        'data-id' => $gpio->id,
        'class' => 'gpioCheckbox'
    ])?>
    <span class="checkBoxSlider round"></span>
   </label>  <?=$gpio->name?> / <?=$gpio->device->name?>
    <br>

<?php endforeach; ?>
</div>
    </div>
<script>
    let urlCommandGpio = "<?=Url::to(['/gpio/request/set'])?>";
</script>
<?php
$this->registerJs(<<<JS
let blockGpioRequest;
let waitGpioRequest;

$(".gpioCheckbox").on('change', function() {
    commandGpio($(this).data('device'), $(this).data('pin'), $(this).prop('checked'))
})

let deviceRepeatGpio = 0;

function commandGpio(deviceId, pin, value) {
    
    if(deviceRepeatGpio === 0)
        deviceRepeatGpio = 1;
    
    if(blockGpioRequest) {
        waitGpioRequest = deviceId;
        return false;
    }
    blockGpioRequest = true;
    waitGpioRequest = false;

    openWaitRequest(deviceId, deviceRepeatGpio);
    $.get(urlCommandGpio, {deviceId:deviceId, pin:pin, value:value}, function(data) {
        blockGpioRequest = false;
        if(waitGpioRequest)
          commandGpio(waitGpioRequest)
        hideWaitRequest(deviceId, deviceRepeatGpio);
        deviceRepeatGpio = 0;
    }).fail(function() {
        blockGpioRequest = false;
        waitGpioRequest = false;
        if(deviceRepeatGpio < 5) {
            deviceRepeatGpio++;
            commandGpio(deviceId, pin, value);
        } else {
            hideWaitRequest(deviceId, deviceRepeatGpio)
            alert( "Ошибка отправки запроса после 5 попыток" );
            deviceRepeatGpio = 0;
        }
    })
}

JS
);