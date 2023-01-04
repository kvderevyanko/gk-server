<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $devices array */
/* @var $device \app\models\Device */

$this->title = 'Главная';
?>
<div class="row">

     <?=\app\components\widgets\DeviceBtnWidget::widget()?>
     <?=\app\components\widgets\WsShowWidget::widget(['mainPage' => true])?>
     <?=\app\components\widgets\DhtShowWidget::widget(['mainPage' => true])?>
    <?=\app\components\widgets\PwmShowWidget::widget(['mainPage' => true])?>
     <?=\app\components\widgets\GpioShowWidget::widget(['mainPage' => true])?>
</div>

