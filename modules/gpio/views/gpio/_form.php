<?php

use app\models\Commands;
use app\models\Device;
use app\modules\gpio\models\Gpio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Gpio */
/* @var $form yii\widgets\ActiveForm */
/* @var $deviceId integer */
?>

<div class="gpio-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <h4>Устройство - <?= Device::deviceName($deviceId) ?></h4>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'pin')->textInput() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'home')->checkbox() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'motor')->checkbox() ?>
            <?= $form->field($model, 'negative')->checkbox() ?>
        </div>
        <div class="col-sm-12" id="conditionBlock">

        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <div class="btn btn-info" id="appendCondition">Добавить условие</div>
            </div>
            <p>
                Для условия указываем границы. Границы считаются: ОТ <=  Включено/Нет > ДО.<br>
                И указываем - активна или нет настройка. Раз в минуту происходит проверка сверху вниз, и результат записывается в базу.<br>
                После этого происходит запрос на устройство.<br>
                Значения можно перетаскивать, менять местами.
            </p>
            <p>
                К примеру, нам нужно что бы чвет был включён с 7 утра до 23 часов, а в остальное время выключен.<br>
                Указываем с 0 до 7 - выключен. С 7 до 23 - включён. С 23 до 24 - выключен.
            </p>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<div id="conditionTemplate" class="hidden">
    <div class="form-group">
    <div class="row conditionTemplate">
        <div class="col-sm-3">
            <?= Html::dropDownList('Commands[conditionType][]', '', Commands::conditionsList(), ['class' => 'form-control']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('Commands[conditionFrom][]', '',
                ['class' => 'form-control', 'type' => 'number', 'placeholder' => 'Значение от']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::textInput('Commands[conditionTo][]', '',
                ['class' => 'form-control', 'type' => 'number', 'placeholder' => 'Значение до']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::hiddenInput('Commands[pinValue][]', '0') ?>
            <label><?= Html::checkbox('Commands[pinValue][]', '', ['class' => 'pinValue']) ?> Включить</label>
        </div>
        <div class="col-sm-2">
            <?= Html::hiddenInput('Commands[active][]', '0') ?>
            <label><?= Html::checkbox('Commands[active][]', '', ['class' => 'active']) ?> Активно</label>
        </div>
        <div class="col-sm-1 text-right">
        <span class="btn btn-warning removeCondition">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </span>
        </div>
    </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS

let conditionBlock = $("#conditionBlock");
let appendCondition = $("#appendCondition");
let conditionTemplate = $("#conditionTemplate");

appendCondition.on('click', function () {
    let template = conditionTemplate.html();
    let index = conditionBlock.find($(".conditionTemplate")).length;
    template = template.replaceAll('[]', '['+index+']')
    conditionBlock.append(template);
    updateSortable(appendCondition)
});

conditionBlock.on('click', '.removeCondition', function () {
    $(this).parents(".conditionTemplate").parent().remove()
})

JS
);