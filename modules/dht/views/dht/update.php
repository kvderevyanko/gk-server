<?php

use app\modules\dht\models\Dht;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Dht */

$this->title = 'Изменить термометр: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Термометры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="dht-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
