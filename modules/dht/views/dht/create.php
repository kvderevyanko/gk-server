<?php

use app\modules\dht\models\Dht;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Dht */

$this->title = 'Добавить термометр';
$this->params['breadcrumbs'][] = ['label' => 'Термометры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dht-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
