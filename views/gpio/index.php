<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'GPIO';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gpio-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить GPIO', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            [
                'attribute' => 'deviceId',
                'value' => function($data) {
                    return \app\models\Device::deviceName($data->deviceId);
                }
            ],
            'name',
            'pin',
            'value:boolean',
            'active:boolean',
            'home:boolean',
            'motor:boolean',
            'negative:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
