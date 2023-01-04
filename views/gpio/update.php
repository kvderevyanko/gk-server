<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Gpio */
/* @var $commands \yii\helpers\Json */

$this->title = 'Изменить GPIO: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'GPIO', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="gpio-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
$this->registerJs(<<<JS

let commands = JSON.parse('$commands');

$.each(commands, function (i, item){
    cl(item)
    let template = $("#conditionTemplate").html();
    template = template.replaceAll('[]', '['+i+']');
    template = $(template);
    template.find('[name="Commands[conditionType]['+i+']"]').val(item['conditionType'])
    template.find('[name="Commands[conditionFrom]['+i+']"]').val(item['conditionFrom'])
    template.find('[name="Commands[conditionTo]['+i+']"]').val(item['conditionTo'])
    
    if(item['pinValue']) 
        template.find('.pinValue').prop('checked', true);
    
    if(item['active']) 
        template.find('.active').prop('checked', true);
    
    $("#conditionBlock").append(template);
})

updateSortable($("#conditionBlock"));

JS
);