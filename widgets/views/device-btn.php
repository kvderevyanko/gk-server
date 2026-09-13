<?php

/* @var $this \yii\web\View */
/* @var $devices array */
/* @var $device \app\models\Device */
?>
<nav class="device-switcher" aria-label="Быстрый переход к устройству">
    <span class="device-switcher__label">Устройства</span>
    <?php foreach ($devices as $device): ?>
        <?=\yii\helpers\Html::a(\yii\helpers\Html::encode($device->name),
            ['/device/control', 'device' => $device->id],
            ['class' => 'device-switcher__item '.$device->class])?>
    <?php endforeach;?>
</nav>
