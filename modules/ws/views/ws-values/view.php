<?php

use app\modules\ws\models\WsValues;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model WsValues */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройка WS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ws-values-view">

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
            'deviceId',
            'name',
            'defaultBuffer',
            'buffer',
            'mode',
            'delay',
            'bright',
            'singleColor',
            'gradientColor:ntext',
            'modeOptions',
            'home:boolean',
            'active:boolean',
        ],
    ]) ?>

</div>
