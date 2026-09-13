<?php

use app\models\Device;
use app\models\DeviceSettings;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $devices Device[] */

$this->title = 'Устройства';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="management-page" aria-labelledby="devices-title">
    <header class="management-page__header">
        <div>
            <p class="management-page__eyebrow">Настройки</p>
            <h2 id="devices-title">Устройства</h2>
            <p>Добавляйте контроллеры и открывайте настройки только поддерживаемой периферии.</p>
        </div>
        <?= Html::a('Добавить устройство', ['create'], ['class' => 'btn btn-success']) ?>
    </header>
    <?php if (!$devices): ?>
        <div class="management-empty">
            <h3>Устройств пока нет</h3>
            <p>Добавьте контроллер, чтобы настроить GPIO, PWM, датчики или ленту.</p>
        </div>
    <?php else: ?>
        <div class="management-grid">
            <?php foreach ($devices as $device): ?>
                <article class="management-card<?= $device->active ? '' : ' is-inactive' ?>">
                    <p class="management-card__eyebrow"><?= $device->active ? 'Активно' : 'Отключено' ?></p>
                    <h3><?= Html::encode($device->name) ?></h3>
                    <p class="management-card__meta"><?= Html::encode($device->host) ?></p>
                    <div class="management-card__links">
                        <?php foreach (DeviceSettings::settingsList() as $key => $name): ?>
                            <?php if (DeviceSettings::checkDeviceSetting($device->id, $key)): ?>
                                <?= Html::a(Html::encode($name), [DeviceSettings::getSettingsUrl($key), 'deviceId' => $device->id], ['class' => 'management-card__feature']) ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="management-card__actions">
                        <?= Html::a('Открыть управление', ['/device/control', 'device' => $device->id], ['class' => 'btn btn-default']) ?>
                        <?= Html::a('Изменить', ['update', 'id' => $device->id], ['class' => 'btn btn-primary']) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
