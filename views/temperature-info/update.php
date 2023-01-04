<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TemperatureInfo */

$this->title = 'Update Temperature Info: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Temperature Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="temperature-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
