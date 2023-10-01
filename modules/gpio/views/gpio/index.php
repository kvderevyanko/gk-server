<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\data\ActiveDataProvider;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $deviceId integer */

$this->title = 'GPIO';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gpio-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить GPIO', ['create', 'deviceId' => $deviceId], ['class' => 'btn btn-success']) ?>
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
