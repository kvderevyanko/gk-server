<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $devices array */
/* @var $device \app\models\Device */

$this->title = 'Главная';
?>
<div class="row">

     <?=\app\components\widgets\DeviceBtnWidget::widget()?>
     <?=\app\modules\ws\widgets\WsShowWidget::widget(['mainPage' => true])?>
     <?=\app\modules\dht\widgets\DhtShowWidget::widget(['mainPage' => true])?>
    <?=\app\modules\pwm\widgets\PwmShowWidget::widget(['mainPage' => true])?>
     <?=\app\modules\gpio\widgets\GpioShowWidget::widget(['mainPage' => true]) ?>
</div>

