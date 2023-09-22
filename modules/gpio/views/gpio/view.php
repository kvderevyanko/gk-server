<?php

use app\modules\gpio\models\Gpio;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Gpio */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'GPIO', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gpio-view">

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
            'pin',
            'value:boolean',
            'active:boolean',
            'home:boolean',
            'motor:boolean',
            'negative:boolean',
        ],
    ]) ?>

</div>
