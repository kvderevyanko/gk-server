<?php

use app\models\DeviceSettings;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'type')
        ->dropDownList(\app\models\Device::typeList(), ['class' => 'form-control']) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'class')->dropDownList(\app\models\Device::btnClass()) ?>
    <?= $form->field($model, 'home')->checkbox() ?>
    <?= $form->field($model, 'active')->checkbox() ?>

    <?php if(!$model->isNewRecord): ?>
    <div class="row">
    <?php foreach (DeviceSettings::settingsList() as $key=>$name): ?>
        <div class="col-sm-3 col-xs-6">
            <?=Html::checkbox("Settings[$key]", DeviceSettings::checkDeviceSetting($model->id, $key), ['label' => $name])?>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
<hr>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
