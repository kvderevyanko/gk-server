<?php

/* @var $this yii\web\View */
/* @var $deviceCards array */
/* @var $sensors array */

$this->title = 'Главная';
?>
<section class="overview-intro" aria-labelledby="overview-title">
    <div>
        <p class="overview-intro__eyebrow">Домашняя панель</p>
        <h2 id="overview-title" class="overview-intro__title">Управление домом</h2>
        <p class="overview-intro__text">Быстрые действия и последние сохранённые показания избранных устройств.</p>
    </div>
    <?= \yii\helpers\Html::a('Настроить устройства', ['/device/index'], ['class' => 'overview-intro__action']) ?>
</section>

<section class="overview-section" aria-labelledby="devices-title">
    <div class="overview-section__header">
        <div>
            <p class="overview-section__eyebrow">Избранное</p>
            <h2 id="devices-title" class="overview-section__title">Устройства</h2>
        </div>
        <p class="overview-section__hint">Открытие карточки не отправляет команду устройству.</p>
    </div>
    <?php if ($deviceCards === []): ?>
        <div class="overview-empty">
            <h3>На обзоре пока нет устройств</h3>
            <p>Добавьте устройство и включите для него признак «На главной» в настройках.</p>
        </div>
    <?php else: ?>
        <div class="device-overview-grid">
            <?php foreach ($deviceCards as $card): ?>
                <?php $device = $card['device']; ?>
                <?= \yii\helpers\Html::a(
                    '<article class="device-overview-card">'
                    . '<p class="device-overview-card__eyebrow">ESP8266 · связь не проверялась</p>'
                    . '<h3 class="device-overview-card__title">' . \yii\helpers\Html::encode($device->name) . '</h3>'
                    . '<dl class="device-overview-card__metrics">'
                    . '<div><dt>Переключатели</dt><dd>' . $card['gpioCount'] . '</dd></div>'
                    . '<div><dt>Датчики</dt><dd>' . $card['sensorCount'] . '</dd></div>'
                    . '</dl><span class="device-overview-card__link">Открыть устройство <span aria-hidden="true">→</span></span>'
                    . '</article>',
                    ['/device/control', 'device' => $device->id],
                    ['class' => 'device-overview-card__anchor']
                ) ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($sensors !== []): ?>
    <section class="overview-section sensor-summary" aria-labelledby="sensors-title">
        <div class="overview-section__header">
            <div>
                <p class="overview-section__eyebrow">Последние данные</p>
                <h2 id="sensors-title" class="overview-section__title">Температура и влажность</h2>
            </div>
            <p class="overview-section__hint">Здесь отображаются сохранённые измерения. Обновление выполняется на странице устройства.</p>
        </div>
        <div class="sensor-summary__grid">
            <?php foreach ($sensors as $item): ?>
                <?php $sensor = $item['sensor']; $reading = $item['reading']; ?>
                <article class="sensor-card">
                    <p class="sensor-card__device"><?= \yii\helpers\Html::encode($sensor->device->name) ?></p>
                    <h3 class="sensor-card__title"><?= \yii\helpers\Html::encode($sensor->name ?: 'DHT, пин ' . $sensor->pin) ?></h3>
                    <?php if ($reading === null): ?>
                        <p class="sensor-card__empty">Измерений ещё нет</p>
                    <?php else: ?>
                        <div class="sensor-card__values">
                            <span><strong><?= number_format((float) $reading->temperature, 1, '.', '') ?>°</strong>Температура</span>
                            <span><strong><?= number_format((float) $reading->humidity, 0, '.', '') ?>%</strong>Влажность</span>
                        </div>
                        <p class="sensor-card__time">Сохранено <?= date('d.m H:i', (int) $reading->datetime) ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?= \app\modules\gpio\widgets\GpioShowWidget::widget(['mainPage' => true]) ?>
<?= \app\modules\pwm\widgets\PwmShowWidget::widget(['mainPage' => true]) ?>
