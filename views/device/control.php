<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $device \app\models\Device */

$this->title = $device->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <?=\app\components\widgets\DeviceBtnWidget::widget()?>
    <div class="col-sm-12"><h3><?=$this->title?></h3><hr></div>
    <?=\app\components\widgets\DhtShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\components\widgets\PwmShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\components\widgets\WsShowWidget::widget(['deviceId' => $device->id])?>
    <?=\app\components\widgets\GpioShowWidget::widget(['deviceId' => $device->id])?>
</div>