<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PwmValues */

$this->title = 'Изменить PWM пин: ' . $model->pinId;
$this->params['breadcrumbs'][] = ['label' => 'PWM пины', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="pwm-values-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
