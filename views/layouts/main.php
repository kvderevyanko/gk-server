<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\models\Settings;
use app\widgets\SettingValueWidget;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="app-body">
    <?php $this->beginBody() ?>
    <a class="skip-link" href="#main-content">Перейти к содержимому</a>
    <div class="app-shell">
        <aside class="app-sidebar" id="primary-navigation" aria-label="Основная навигация">
            <div class="app-brand">
                <?= Html::a(Html::encode(SettingValueWidget::widget(['key' => Settings::SITE_NAME])), ['/site/index'], ['class' => 'app-brand__link']) ?>
                <span class="app-brand__caption">Панель управления домом</span>
            </div>
            <nav class="app-nav" aria-label="Разделы">
                <?= Html::a('Обзор', ['/site/index'], ['class' => 'app-nav__link']) ?>
                <span class="app-nav__heading">Устройства</span>
                <?php foreach (\app\models\Device::getActiveDevices() as $navigationDevice): ?>
                    <?= Html::a(Html::encode($navigationDevice->name), ['/device/control', 'device' => $navigationDevice->id], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?php endforeach; ?>
                <span class="app-nav__heading">Система</span>
                <?= Html::a('Устройства', ['/device/index'], ['class' => 'app-nav__link']) ?>
                <span class="app-nav__heading">Настройка периферии</span>
                <?= Html::a('PWM: параметры', ['/pwm/pwm-settings/index'], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?= Html::a('PWM: каналы', ['/pwm/pwm-values/index'], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?= Html::a('GPIO', ['/gpio/gpio/index'], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?= Html::a('DHT', ['/dht/dht/index'], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?= Html::a('WS2812', ['/ws/ws-values/index'], ['class' => 'app-nav__link app-nav__link--device']) ?>
                <?= Html::a('Справка', ['/help/index'], ['class' => 'app-nav__link']) ?>
            </nav>
        </aside>
        <div class="app-workspace">
            <header class="app-header">
                <button class="app-menu-button" type="button" aria-controls="primary-navigation" aria-expanded="false">
                    <span class="app-menu-button__icon" aria-hidden="true"></span>
                    <span class="sr-only">Открыть навигацию</span>
                </button>
                <div>
                    <p class="app-header__eyebrow">ESP Home</p>
                    <h1 class="app-header__title"><?= Html::encode($this->title) ?></h1>
                </div>
            </header>
            <main class="app-content" id="main-content" tabindex="-1">
                <?= $content ?>
            </main>
        </div>
    </div>
    <?= $this->render('_wait_request') ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
