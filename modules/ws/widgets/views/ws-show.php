<?php

use app\modules\ws\models\WsValues;
use app\modules\ws\widgets\assets\WsAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $wsValues WsValues[] */

WsAsset::register($this);
?>
<section class="control-panel ws-panel"
         data-ws-panel
         data-command-url="<?= Url::to(['/ws/ws/request']) ?>"
         aria-labelledby="ws-title">
    <header class="control-panel__header">
        <div>
            <p class="control-panel__eyebrow">Свет</p>
            <h2 id="ws-title" class="control-panel__title">Адресная лента WS2812</h2>
        </div>
        <p class="control-panel__hint">Настройка применяется после завершения действия и требует подтверждения устройства.</p>
    </header>
    <div class="ws-grid">
        <?php foreach ($wsValues as $ws): ?>
            <?php
            $bufferMax = min(330, max(1, (int) $ws->defaultBuffer * 3));
            $buffer = min($bufferMax, max(1, (int) $ws->buffer));
            $delay = min(300, max(20, (int) $ws->delay));
            $bright = min(255, max(1, (int) $ws->bright));
            $modeOptions = min(255, max(1, (int) $ws->modeOptions));
            $mode = array_key_exists($ws->mode, WsValues::$modeList) ? $ws->mode : 'off';
            $color = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $ws->singleColor) ? $ws->singleColor : '#ffffff';
            ?>
            <article class="ws-card" data-ws-card data-device="<?= (int) $ws->deviceId ?>">
                <p class="control-card__device"><?= Html::encode($ws->device->name) ?></p>
                <h3 class="control-card__title"><?= Html::encode($ws->name ?: 'WS2812') ?></h3>
                <p class="control-card__meta">Базовая длина ленты: <?= (int) $ws->defaultBuffer ?> LED</p>
                <div class="ws-card__controls">
                    <label class="ws-field ws-field--wide">
                        <span>Режим</span>
                        <?= Html::dropDownList('mode', $mode, WsValues::$modeList, ['class' => 'ws-control', 'data-ws-field' => true]) ?>
                    </label>
                    <label class="ws-field">
                        <span>Количество LED</span>
                        <span class="ws-field__range">
                            <input class="ws-control" data-ws-field name="buffer" type="range" min="1" max="<?= $bufferMax ?>" step="1" value="<?= $buffer ?>">
                            <output data-ws-output><?= $buffer ?></output>
                        </span>
                    </label>
                    <label class="ws-field">
                        <span>Яркость</span>
                        <span class="ws-field__range">
                            <input class="ws-control" data-ws-field name="bright" type="range" min="1" max="255" step="1" value="<?= $bright ?>">
                            <output data-ws-output><?= $bright ?></output>
                        </span>
                    </label>
                    <label class="ws-field">
                        <span>Задержка, мс</span>
                        <span class="ws-field__range">
                            <input class="ws-control" data-ws-field name="delay" type="range" min="20" max="300" step="1" value="<?= $delay ?>">
                            <output data-ws-output><?= $delay ?></output>
                        </span>
                    </label>
                    <label class="ws-field">
                        <span>Опция режима</span>
                        <span class="ws-field__range">
                            <input class="ws-control" data-ws-field name="modeOptions" type="range" min="1" max="255" step="1" value="<?= $modeOptions ?>">
                            <output data-ws-output><?= $modeOptions ?></output>
                        </span>
                    </label>
                    <label class="ws-field ws-field--wide">
                        <span>Основной цвет</span>
                        <input class="ws-control" data-ws-field name="singleColor" type="color" value="<?= Html::encode($color) ?>">
                    </label>
                </div>
                <p class="control-card__status" data-ws-status aria-live="polite">Текущая конфигурация сохранена</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
