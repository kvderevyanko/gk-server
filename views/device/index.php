<?php

use app\models\Device;
use app\models\DeviceSettings;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $devices array */
/* @var $device Device */

$this->title = 'Устройства';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить устройство', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="row">
        <?php foreach ($devices as $device): ?>
            <div class="col-md-4 col-sm-6">
                <div class="panel <?= str_replace('btn-', 'panel-', $device->class) ?>">
                    <div class="panel-heading ">
                        <?= Html::a(
                            '<span class="glyphicon glyphicon-' . (($device->active) ? 'ok' : 'remove') . '" 
                       aria-hidden="true"></span>',
                            ['update', 'id' => $device->id],
                            ['class' => 'btn btn-xs pull-right ' . (($device->active) ? 'btn-success' : 'btn-danger')]
                        ) ?>
                        <h3 class="panel-title"><?= $device->host ?></h3>
                    </div>
                    <div class="panel-body device-setting-panel">
                        <h5><?= $device->name ?></h5>

                        <?php foreach (DeviceSettings::settingsList() as $key => $name): ?>
                            <?php if (DeviceSettings::checkDeviceSetting($device->id, $key)): ?>
                                <?=Html::a($name, [DeviceSettings::getSettingsUrl($key), 'deviceId' => $device->id], ['class' => 'btn btn-info'])?>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php
$this->registerJs(<<<JS
function setPanelHeight(){
    let h = 0;
    let block = $(".device-setting-panel");
    $.each(block, function (){
        if($(this).height() > h) {
          h = $(this).height();
        }
    });
    block.height(h);
}

setPanelHeight();

JS
);