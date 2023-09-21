<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\ws\models\DbWsValues */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ws-values-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'deviceId')
        ->dropDownList(\app\models\Device::devicesList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'defaultBuffer')->textInput() ?>

    <?php Modal::begin([
        'size' => Modal::SIZE_LARGE,
        'id' => 'fullSettings'
    ]) ?>

    <?= $form->field($model, 'buffer')->textInput() ?>

    <?= $form->field($model, 'mode')->dropDownList(\app\modules\ws\models\DbWsValues::$modeList) ?>

    <?= $form->field($model, 'delay')->textInput() ?>

    <?= $form->field($model, 'bright')->textInput() ?>

    <?= $form->field($model, 'singleColor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gradientColor')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'modeOptions')->textInput(['maxlength' => true]) ?>

    <?php Modal::end() ?>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fullSettings">
        Все настройки
    </button>
    <hr />

    <?= $form->field($model, 'home')->checkbox() ?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
