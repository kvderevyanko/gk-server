<?php

use app\modules\dht\models\TemperatureInfo;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model TemperatureInfo */

$this->title = 'Create Temperature Info';
$this->params['breadcrumbs'][] = ['label' => 'Temperature Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="temperature-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
