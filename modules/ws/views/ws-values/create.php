<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\DbWsValues */

$this->title = 'Создание Ws настроек';
$this->params['breadcrumbs'][] = ['label' => 'Настройка WS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ws-values-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
