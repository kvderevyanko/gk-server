<?php

/* @var $this \yii\web\View */
/* @var $devices array */
/* @var $device \app\models\Device */
?>
<div class="col-sm-12" style="margin-bottom: 15px">
    <?php foreach ($devices as $device): ?>
        <?=\yii\helpers\Html::a($device->name,
            ['/device/control', 'device' => $device->id],
            ['class' => 'btn '.$device->class])?>
    <?php endforeach;?>
</div>