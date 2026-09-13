<?php

use app\components\CustomHelper;
use app\modules\dht\models\Dht;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dhtList Dht[] */
?>
<section class="control-panel dht-panel"
         data-dht-panel
         data-command-url="<?= Url::to(['/dht/request/get-temperature']) ?>"
         data-graph-url="<?= Url::to(['/dht/request/get-graph-info']) ?>"
         aria-labelledby="dht-title">
    <header class="control-panel__header">
        <div>
            <p class="control-panel__eyebrow">Климат</p>
            <h2 id="dht-title" class="control-panel__title">Температура и влажность</h2>
        </div>
        <p class="control-panel__hint">Показания запрашиваются только по кнопке и сохраняются в истории для графика.</p>
    </header>
    <div class="dht-grid">
        <?php foreach ($dhtList as $dht): ?>
            <?php
            $lastTemperature = $dht->lastTemperature($dht->pin);
            $id = 'dht-' . $dht->id;
            $sensorName = $dht->name ?: 'DHT, пин ' . $dht->pin;
            ?>
            <article class="dht-card" data-dht-card>
                <p class="control-card__device"><?= Html::encode($dht->device->name) ?></p>
                <h3 class="control-card__title"><?= Html::encode($sensorName) ?></h3>
                <p class="control-card__meta">Пин <?= Html::encode($dht->pin) ?></p>
                <div class="dht-card__readings" data-dht-readings>
                    <?php if ($lastTemperature): ?>
                        <span><strong><?= Html::encode($lastTemperature->temperature) ?>°</strong>Температура</span>
                        <span><strong><?= Html::encode($lastTemperature->humidity) ?>%</strong>Влажность</span>
                    <?php else: ?>
                        <p>Измерений ещё нет</p>
                    <?php endif; ?>
                </div>
                <p class="dht-card__time" data-dht-time>
                    <?= $lastTemperature ? 'Сохранено ' . Html::encode(CustomHelper::formatDateTime($lastTemperature->datetime)) : 'Обновите датчик, чтобы получить первое значение' ?>
                </p>
                <div class="dht-card__actions">
                    <button class="dht-update" type="button" data-device="<?= (int) $dht->deviceId ?>" data-pin="<?= (int) $dht->pin ?>">Обновить</button>
                    <button class="dht-graph" type="button" data-device="<?= (int) $dht->deviceId ?>" data-pin="<?= (int) $dht->pin ?>" aria-controls="<?= $id ?>-chart" aria-expanded="false">История</button>
                </div>
                <p class="control-card__status" data-dht-status aria-live="polite">Показано последнее сохранённое измерение</p>
                <div id="<?= $id ?>-chart" class="dht-card__chart" hidden>
                    <canvas data-dht-chart></canvas>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
