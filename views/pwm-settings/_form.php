<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PwmSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pwm-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceId')
        ->dropDownList(\app\models\Device::devicesList()) ?>

    <?= $form->field($model, 'clock')->textInput() ?>

    <?= $form->field($model, 'duty')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
