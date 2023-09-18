<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройка WS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ws-values-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить Ws настройку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          // 'id',
            [
                'attribute' => 'deviceId',
                'value' => function($data) {
                    return \app\models\Device::deviceName($data->deviceId);
                }
            ],
            'name',
            'defaultBuffer',
            'buffer',
            'mode',
            'delay',
            'bright',
            //'singleColor',
            //'gradientColor:ntext',
            //'modeOptions',
            'home:boolean',
            'active:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
