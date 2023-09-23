<?php

use app\modules\pwm\models\PwmValues;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model PwmValues */

$this->title = 'Изменить PWM пин: ' . $model->pin;
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
