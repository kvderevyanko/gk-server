<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settings;

/* @var $this yii\web\View */
/* @var $settings array */

$this->title = 'Дополнительные настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php ActiveForm::begin() ?>
    <div class="row m-t-15">
        <div class="col-sm-2"><label>Заголовок сайта:</label></div>
        <div class="col-sm-4"><?= Html::textInput(
                'Settings[' . Settings::SITE_NAME . ']',
                $settings[Settings::SITE_NAME]->value,
                ['class' => 'form-control']
            ) ?></div>
    </div>
    <div class="row m-t-15">
        <div class="col-sm-2"><label>Интервал для мотора (в минутах):</label></div>
        <div class="col-sm-4"><?= Html::textInput(
                'Settings[' . Settings::MOTOR_INTERVAL . ']',
                $settings[Settings::MOTOR_INTERVAL]->value,
                ['class' => 'form-control']
            ) ?></div>
    </div>
    <hr>
    <button type="submit" class="btn btn-info">Сохранить</button>
    <?php ActiveForm::end() ?>
</div>
