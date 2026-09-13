<?php

use app\modules\gpio\models\Gpio;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $gpioValues Gpio[] */
?>
<section class="control-panel gpio-panel" aria-labelledby="gpio-title">
    <header class="control-panel__header">
        <div>
            <p class="control-panel__eyebrow">Быстрое управление</p>
            <h2 id="gpio-title" class="control-panel__title">Переключатели</h2>
        </div>
        <p class="control-panel__hint">Изменения отправляются на устройство и показывают результат доставки.</p>
    </header>
    <div class="control-grid">
        <?php foreach ($gpioValues as $gpio): ?>
            <?php $controlId = 'gpio-control-' . $gpio->id; ?>
            <article class="control-card" data-gpio-card>
                <div class="control-card__content">
                    <p class="control-card__device"><?= Html::encode($gpio->device->name) ?></p>
                    <h3 class="control-card__title"><?= Html::encode($gpio->name ?: 'GPIO ' . $gpio->pin) ?></h3>
                    <p class="control-card__meta">Пин <?= Html::encode($gpio->pin) ?></p>
                    <p class="control-card__status" data-gpio-status aria-live="polite">Текущее состояние сохранено</p>
                </div>
                <div class="control-card__action">
                    <?= Html::checkbox('', (bool) $gpio->value, [
                        'id' => $controlId,
                        'class' => 'gpio-control',
                        'data-url' => \yii\helpers\Url::to(['/gpio/request/set']),
                        'data-pin' => $gpio->pin,
                        'data-device' => $gpio->deviceId,
                        'aria-describedby' => $controlId . '-state',
                    ]) ?>
                    <?= Html::label('<span class="gpio-control__track" aria-hidden="true"></span><span class="sr-only">Переключить ' . Html::encode($gpio->name ?: 'GPIO ' . $gpio->pin) . '</span>', $controlId, ['class' => 'gpio-control__label', 'encode' => false]) ?>
                    <span class="control-card__state" id="<?= $controlId ?>-state" data-gpio-state><?= $gpio->value ? 'Включено' : 'Выключено' ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
