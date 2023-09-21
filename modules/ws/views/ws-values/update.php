<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\ws\models\DbWsValues */

$this->title = 'Изменить Ws настройку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройка WS', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="ws-values-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
