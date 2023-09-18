<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\components\widgets\SettingValueWidget;
use app\models\Settings;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => SettingValueWidget::widget(['key' => Settings::SITE_NAME]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            [
                'label' => 'Настройки',
                'url' => '#',
                'items' => [
                    ['label' => 'Устройства', 'url' => ['/device/index']],
                    [
                        'label' => 'PWM',
                        'items' => [
                            ['label' => 'Установка PWM', 'url' => ['/pwm/pwm-settings/index']],
                            ['label' => 'Настройка PWM пинов', 'url' => ['/pwm/pwm-values/index']],
                        ]
                    ],
                    ['label' => 'Настройка WS', 'url' => ['/ws-values/index']],
                    ['label' => 'Настройка GPIO', 'url' => ['/gpio/gpio/index']],
                    //['label' => 'Настройка Термометра', 'url' => ['/dht/index']],
                    //['label' => 'Управление SSD 1306', 'url' => ['/ssd1306/default/index']],
                    ['label' => 'Дополнительные настройки', 'url' => ['/settings/index']],
                    //['label' => 'Джойстик', 'url' => ['/gamepad/index']],
                ]
            ],

        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>

        <p class="pull-right"></p>
    </div>
</footer>
<?=$this->render('_wait_request')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
