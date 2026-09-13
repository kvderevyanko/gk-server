<?php

/* @var $this yii\web\View */
/* @var $device \app\models\Device */

$this->title = $device->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="device-page">
    <?= \app\widgets\DeviceBtnWidget::widget()?>
    <header class="device-page__header"><p>Устройство</p><h2><?=\yii\helpers\Html::encode($this->title)?></h2><span>Управление и данные устройства</span></header>
    <?= \app\widgets\CommandDeliveryWidget::widget(['deviceId' => $device->id]) ?>
    <?=\app\modules\dht\widgets\DhtShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\ws\widgets\WsShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\gpio\widgets\GpioShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\modules\pwm\widgets\PwmShowWidget::widget(['deviceId' => $device->id])?>
</div>
