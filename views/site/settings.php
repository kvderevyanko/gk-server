<?php
use yii\helpers\Html;
$this->title = 'Настройки';
$sections = [
    ['Устройства', 'Добавление ESP и отображение на обзоре', ['/device/index'], 'Основное'],
    ['GPIO', 'Пины, переключатели и расписания', ['/gpio/gpio/index'], 'Управление'],
    ['PWM', 'Каналы яркости и параметры частоты', ['/pwm/pwm-values/index'], 'Управление'],
    ['Параметры PWM', 'Частота и совместимые настройки устройства', ['/pwm/pwm-settings/index'], 'Управление'],
    ['Датчики DHT', 'Температура и влажность', ['/dht/dht/index'], 'Датчики'],
    ['WS2812', 'Световые эффекты и цвета', ['/ws/ws-values/index'], 'Свет'],
    ['Параметры панели', 'Название и интервал мотора', ['/settings/index'], 'Система'],
];
?>
<section class="settings-hero"><p>Конфигурация</p><h2>Настройки дома</h2><span>Технические параметры отделены от ежедневного управления.</span></section>
<div class="settings-grid">
<?php foreach ($sections as $section): ?>
<?= Html::a('<article class="settings-card"><p>' . Html::encode($section[3]) . '</p><h3>' . Html::encode($section[0]) . '</h3><span>' . Html::encode($section[1]) . '</span><b>Открыть <i aria-hidden="true">→</i></b></article>', $section[2], ['class' => 'settings-card__link']) ?>
<?php endforeach; ?>
</div>
