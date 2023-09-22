<?php

use app\modules\pwm\models\PwmSettings;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model PwmSettings */

$this->title = 'Установка настроек PWM';
$this->params['breadcrumbs'][] = ['label' => 'PWM настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pwm-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
