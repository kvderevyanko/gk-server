<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\models\Settings;
use app\widgets\SettingValueWidget;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

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
    <body>
    <?php $this->beginBody() ?>


    <div class="wrapper">

        <?= app\widgets\SideBar::widget([
            //'bgImage'=>'@web/img/sidebar-5.jpg', //Don't define it if there is none
            'header'=>[
                'title'=>SettingValueWidget::widget(['key' => Settings::SITE_NAME]),
                'url'=>['/']
            ],
            'links'=>[
                ['title'=>'Some URL', 'url'=>['/controller/action'], 'icon'=>'users']
            ]
        ]) ?>

        <div class="main-panel">
            <?= app\widgets\NavBar::widget([
                'theme'=>'red',
                'brand'=>[
                   // 'label'=>'Nasafiri'
                ],
                'title' => $this->title,
                'links'=>[
                    ['label' => 'Главная', 'url' => ['/site/index']],
                    ['label' => 'Справка', 'url' => ['/help/index']],
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
                            ['label' => 'Настройка WS', 'url' => ['/ws/ws-values/index']],
                            ['label' => 'Настройка GPIO', 'url' => ['/gpio/gpio/index']],
                            ['label' => 'Настройка Термометра', 'url' => ['/dht/dht/index']],
                            //['label' => 'Управление SSD 1306', 'url' => ['/ssd1306/default/index']],
                            ['label' => 'Дополнительные настройки', 'url' => ['/settings/index']],
                            //['label' => 'Джойстик', 'url' => ['/gamepad/index']],
                        ]
                    ],
                ],
            ]) ?>

            <div class="content">
                <div class="container-fluid">
                    <?= $content ?>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">

                </div>
            </footer>

        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>