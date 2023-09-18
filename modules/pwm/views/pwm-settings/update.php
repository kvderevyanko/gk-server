<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \modules\pwm\models\PwmSettings */

$this->title = 'Изменить PWM настройки: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'PWM настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="pwm-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
