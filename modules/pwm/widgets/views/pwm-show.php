<?php
use app\modules\pwm\models\Pwm;
use yii\helpers\Html;
/* @var $pwmValues Pwm[] */
?>
<section class="control-panel pwm-panel" aria-labelledby="pwm-title">
    <header class="control-panel__header"><div><p class="control-panel__eyebrow">Яркость и мощность</p><h2 id="pwm-title" class="control-panel__title">PWM</h2></div><p class="control-panel__hint">Значение применяется после отпускания ползунка и требует подтверждения устройства.</p></header>
    <div class="pwm-grid">
        <?php foreach ($pwmValues as $pwm): ?>
            <?php $id = 'pwm-control-' . $pwm->id; ?>
            <article class="pwm-card" data-pwm-card>
                <p class="control-card__device"><?= Html::encode($pwm->device->name) ?></p>
                <h3 class="control-card__title"><?= Html::encode($pwm->name ?: 'PWM ' . $pwm->pin) ?></h3>
                <p class="control-card__meta">Пин <?= Html::encode($pwm->pin) ?></p>
                <div class="pwm-card__range"><input id="<?= $id ?>" class="pwm-control" type="range" min="0" max="1023" step="1" value="<?= (int) $pwm->value ?>" data-url="<?= \yii\helpers\Url::to(['/pwm/request/set']) ?>" data-device="<?= $pwm->deviceId ?>" data-pin="<?= $pwm->pin ?>" aria-describedby="<?= $id ?>-status"><output data-pwm-value for="<?= $id ?>"><?= (int) $pwm->value ?></output></div>
                <p id="<?= $id ?>-status" class="control-card__status" data-pwm-status aria-live="polite">Текущее состояние сохранено</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
