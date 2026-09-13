<?php

use app\components\CustomHelper;
use app\models\CommandDelivery;
use yii\helpers\Html;

/* @var $deliveries CommandDelivery[] */
?>
<section class="control-panel delivery-panel" aria-labelledby="delivery-title">
    <header class="control-panel__header">
        <div>
            <p class="control-panel__eyebrow">Журнал доставки</p>
            <h2 id="delivery-title" class="control-panel__title">Последние команды</h2>
        </div>
        <p class="control-panel__hint">Это подтверждение ответа ESP, а не измерение физического состояния вывода.</p>
    </header>
    <?php if (!$deliveries): ?>
        <p class="delivery-panel__empty">Команд этому устройству ещё не отправляли.</p>
    <?php else: ?>
        <ol class="delivery-list">
            <?php foreach ($deliveries as $delivery): ?>
                <?php
                $confirmed = $delivery->status === CommandDelivery::STATUS_CONFIRMED;
                $description = CommandDelivery::description($delivery);
                ?>
                <li class="delivery-list__item<?= $confirmed ? ' is-confirmed' : ' is-error' ?>">
                    <div>
                        <p class="delivery-list__meta"><?= Html::encode($description['category']) ?></p>
                        <h3 class="delivery-list__target"><?= Html::encode($description['target']) ?></h3>
                        <p class="delivery-list__command"><?= Html::encode($description['command']) ?></p>
                        <?php if (!$confirmed): ?>
                            <p class="delivery-list__message"><?= Html::encode($delivery->message) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="delivery-list__result">
                        <strong><?= $confirmed ? 'Подтверждено' : 'Не подтверждено' ?></strong>
                        <time datetime="<?= date(DATE_ATOM, (int) $delivery->createdAt) ?>"><?= Html::encode(CustomHelper::formatDateTime((int) $delivery->createdAt)) ?></time>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</section>
