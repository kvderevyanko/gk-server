<?php

use app\modules\ws\models\DbWsValues;
use app\modules\ws\models\WsValues;
use app\modules\ws\widgets\assets\WsAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $wsValues array */
/* @var $ws DbWsValues */

WsAsset::register($this);
?>
    <div class="col-sm-6">
<h4>WS2812</h4>
<?php foreach ($wsValues as $ws): ?>
<div id="ws_block_<?=$ws->deviceId?>" class="ws_block">
    <label><?=$ws->name?> / <?=$ws->device->name?> / <?=$ws->defaultBuffer?></label><br>
    <label>Режим</label>
    <?=Html::dropDownList('mode', $ws->mode, WsValues::$modeList, ['class' => 'form-control', 'data-device' => $ws->deviceId])?><br>
    <label>Количество диодов</label>
    <div class="slider_block">
    <input
            name="buffer"
            type="range"
            min="1"
            max="<?=$ws->defaultBuffer * 3?>"
            step="1"
            value="<?=$ws->buffer?$ws->buffer:1?>"
            data-device="<?=$ws->deviceId?>"
            data-type="buffer"
            class="slider sliderWs"
    ><span class="badge"><?=$ws->buffer?$ws->buffer:1?></span>
    </div><br>
    <label>Яркость</label>
    <div class="slider_block">
    <input
            name="bright"
            type="range"
            min="0"
            max="255"
            step="1"
            value="<?=$ws->bright?$ws->bright:0?>"
            data-device="<?=$ws->deviceId?>"
            data-type="bright"
            class="slider sliderWs"
    > <span class="badge"><?=$ws->bright?$ws->bright:0?></span>
    </div> <br>
    <label>Задержка</label>
    <div class="slider_block">
    <input
            name="delay"
            type="range"
            min="5"
            max="300"
            step="1"
            value="<?=$ws->delay?$ws->delay:10?>"
            data-device="<?=$ws->deviceId?>"
            data-type="delay"
            class="slider sliderWs"
    ><span class="badge"><?=$ws->delay?$ws->delay:10?></span>
    </div> <br>
    <!--<label>Градиент</label>
    <input name="gradientColor" type="text" value="<?/*=$ws->gradientColor*/?>" data-device="<?/*=$ws->deviceId*/?>"><br>
    --><label>Опции режима</label>
    <div class="slider_block">
        <input
                name="modeOptions"
                type="range"
                min="1"
                max="20"
                step="1"
                value="<?=$ws->modeOptions?$ws->modeOptions:1?>"
                data-device="<?=$ws->deviceId?>"
                data-type="delay"
                class="slider sliderWs"
        ><span class="badge"><?=$ws->modeOptions?$ws->modeOptions:1?></span>
    </div> <br>
    <label>Одиночный цвет</label>
    <input name="singleColor" type="color" value="<?=$ws->singleColor?>" data-device="<?=$ws->deviceId?>"><br><br>
    <div class="btn btn-success saveAnimation hidden" data-device="<?=$ws->deviceId?>">Сохранить анимацию</div>
</div>
<hr>
<?php endforeach; ?>
    </div>

<?php /*
Modal::begin();
?>


<?php
Modal::end(); */
?>

<script>
    let urlCommandWs = "<?=\yii\helpers\Url::to(['/ws/ws/request'])?>";
    //let urlCommandWs = "<?=\yii\helpers\Url::to(['/commands/ws'])?>";
</script>
