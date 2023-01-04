<?php

use yii\helpers\Html;

/* @var $this yii\web\View */


$this->title = 'Дисплей SSD 1306';
$this->params['breadcrumbs'][] = ['label' => 'Дисплей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ws-values-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=Html::a('Рисование пикселей', ['draw'], ['class' => 'btn btn-info'])?>

</div>
