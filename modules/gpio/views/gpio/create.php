<?php

use app\modules\gpio\models\Gpio;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Gpio */
/* @var $deviceId integer */

$this->title = 'Добавить GPIO';
$this->params['breadcrumbs'][] = ['label' => 'GPIO', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gpio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'deviceId' => $deviceId,
    ]) ?>

</div>
