<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\models\TemperatureInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="temperature-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceId')->textInput() ?>

    <?= $form->field($model, 'temperature')->textInput() ?>

    <?= $form->field($model, 'humidity')->textInput() ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
