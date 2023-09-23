<?php

use app\modules\pwm\models\Pwm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Pwm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pwm-values-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceId')
        ->dropDownList(\app\models\Device::devicesList()) ?>

    <?= $form->field($model, 'pin')->textInput() ?>
    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'home')->checkbox() ?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
