<?php

use app\modules\gpio\models\Gpio;
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

    <?=\yii\helpers\Html::checkbox('', ($gpio->value?$gpio->value:0), [
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
    let urlCommandGpio = "<?=Url::to(['/gpio/gpio/request'])?>";
</script>
<?php
$this->registerJs(<<<JS

let gpioBlock = $("#gpioBlock");
let blockGpioRequest;
let waitGpioRequest;

$(".gpioCheckbox").on('change', function() {
    commandGpio($(this).data('device'))
})

let deviceRepeatGpio = 0;

function commandGpio(deviceId) {
    
    if(deviceRepeatGpio === 0)
        deviceRepeatGpio = 1;
    
    if(blockGpioRequest) {
        waitGpioRequest = deviceId;
        return false;
    }
    blockGpioRequest = true;
    waitGpioRequest = false;
    
    let request = {deviceId:deviceId};
    $.each(gpioBlock.find('[data-device="'+deviceId+'"]'), function() {
        request[$(this).data('id')] = $(this).prop('checked')?1:0;
    })
    openWaitRequest(deviceId, deviceRepeatGpio);
    $.get(urlCommandGpio, request, function(data) {
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
            commandGpio(deviceId);
        } else {
            hideWaitRequest(deviceId, deviceRepeatGpio)
            alert( "Ошибка отправки запроса после 5 попыток" );
            deviceRepeatGpio = 0;
        }
        
    })
}

JS
);