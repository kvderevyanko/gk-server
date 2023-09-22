<?php

use app\models\Device;
use app\modules\pwm\models\PwmValues;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model PwmValues */

$this->title = $model->pinId;
$this->params['breadcrumbs'][] = ['label' => 'PWM пины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pwm-values-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'deviceId',
                'value' => function($data) {
                    return Device::deviceName($data->deviceId);
                }
            ],
            'pinId',
            'name',
            'value',
            'home:boolean',
            'active:boolean',
        ],
    ]) ?>

</div>
