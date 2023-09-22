<?php

use app\modules\pwm\models\PwmValues;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model PwmValues */

$this->title = 'Добавить пин';
$this->params['breadcrumbs'][] = ['label' => 'PWM пины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pwm-values-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
