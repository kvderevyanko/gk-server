<?php

/* @var $this yii\web\View */
/* @var $device \app\models\Device */

$this->title = $device->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?= \app\widgets\DeviceBtnWidget::widget()?>
    <div class="col-sm-12"><h3><?=$this->title?></h3><hr></div>
    <?=\app\modules\dht\widgets\DhtShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\pwm\widgets\PwmShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\ws\widgets\WsShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\gpio\widgets\GpioShowWidget::widget(['deviceId' => $device->id])?>
</div>