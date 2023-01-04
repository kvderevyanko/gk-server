<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PWM настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pwm-settings-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить PWM настройку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          //  'id',
            [
                'attribute' => 'deviceId',
                'value' => function($data) {
                    return \app\models\Device::deviceName($data->deviceId);
                }
            ],
            //'deviceId',
            'clock',
            'duty',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
