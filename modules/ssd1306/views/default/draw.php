<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */


$this->title = 'Рисование пикселей';
$this->params['breadcrumbs'][] = ['label' => 'Дисплей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\app\modules\ssd1306\assets\DrawAsset::register($this);
?>
<div class="ws-values-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    <div class="row">
        <div class="col-sm-3"><label>Размер Ш/В пикселей <br>Размер ячейки для рисования</label></div>
        <div class="col-sm-3"><?= Html::dropDownList('', '', [],
                ['class' => 'form-control', 'id' => 'widthPx']
            ) ?></div>
        <div class="col-sm-3"><?= Html::dropDownList('', '', [],
                ['class' => 'form-control', 'id' => 'heightPx']
            ) ?></div>
        <div class="col-sm-3"><?= Html::input('range', '', 20,
                ['id' => 'sizeTd', 'min' => 6, 'max' => 50, 'step' => 1]
            ) ?>
        </div>
        <hr class="col-xs-12">
        <div class="col-sm-3" id="btnColorBlock">
            <span class="btnDraw" style="background-color: #FFF" data-color="#FFF" data-int="0"></span>
            <span class="btnDraw" style="background-color: #000" data-color="#000" data-int="1"></span>
        </div>
        <div class="col-sm-3 text-right" id="prevImageBlock">

        </div>
        <div class="col-sm-6 text-right">
            <?= Html::button('Сохранить', ['class' => 'btn btn-success', 'id' => 'saveDraw']) ?>
        </div>
        <div class="col-sm-12" style="margin-top: 10px" id="tableDraw"></div>
        <div class="col-sm-12" id="imagesList"></div>
    </div>
</div>
<script>
    let urlSaveImage = "<?=Url::to(['save-image'])?>";
    let urlGetImagesList = "<?=Url::to(['get-images-list'])?>";
</script>