<?php

use app\modules\pwm\models\DbPwmValues;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $pwmValues array */
/* @var $pwm DbPwmValues */



?>
    <div class="col-sm-6">
<h4>PWM</h4>
<div id="pwmBlock">
<?php foreach ($pwmValues as $pwm): ?>
<label><?=$pwm->name?> / <?=$pwm->device->name?></label>
        <input
                type="range"
                min="0"
                max="1020"
                step="5"
                value="<?=$pwm->value?$pwm->value:0?>"
                data-pin="<?=$pwm->pinId?>"
                data-device="<?=$pwm->deviceId?>"
                data-id="<?=$pwm->id?>"
                class="slider sliderPwm"
<br>

<?php endforeach; ?>
</div>
    </div>
<script>
    let urlCommandPwm = "<?=Url::to(['/pwm/pwm/request'])?>";
</script>
<?php
$this->registerJs(<<<JS

let pwmBlock = $("#pwmBlock");
let blockPwmRequest;
let waitPwmRequest;

$(".sliderPwm").on('change', function() {
    commandPwm($(this).data('device'))
})

let deviceRepeatPwm = 0;

function commandPwm(deviceId) {
    
    if(deviceRepeatPwm === 0)
        deviceRepeatPwm = 1;
    
    if(blockPwmRequest) {
        waitPwmRequest = deviceId;
        return false;
    }
    blockPwmRequest = true;
    waitPwmRequest = false;
    
    let request = {deviceId:deviceId};
    $.each(pwmBlock.find('[data-device="'+deviceId+'"]'), function() {
        request[$(this).data('id')] = $(this).val();
    })
    openWaitRequest(deviceId, deviceRepeatPwm);
    $.get(urlCommandPwm, request, function(data) {
        blockPwmRequest = false;
      if(waitPwmRequest)
          commandPwm(waitPwmRequest)
      hideWaitRequest(deviceId, deviceRepeatPwm);
      deviceRepeatPwm = 0;
    }).fail(function() {
        blockPwmRequest = false;
        waitPwmRequest = false;
        if(deviceRepeatPwm < 5) {
            deviceRepeatPwm++;
            commandPwm(deviceId);
        } else {
            hideWaitRequest(deviceId, deviceRepeatPwm)
            alert( "Ошибка отправки запроса после 5 попыток" );
            deviceRepeatPwm = 0;
        }
        
    })
}

JS
);