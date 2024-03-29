<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$urlManager = require __DIR__ . '/_urlManager.php';

$config = [
    'id' => 'basic',
    'name' => 'Сервер ESP',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'KLWD74JRl9MVnVt02AgZq2O7Ic5F7pK2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $urlManager,
        ],
        'assetManager' => [
            'linkAssets' => true,
            'bundles' => [
                'deyraka\materialdashboard\web\MaterialDashboardAsset',
            ],
        ],
    ],
    'modules' => [
        'ssd1306' => [
            'class' => 'app\modules\ssd1306\Module',
        ],
        'gpio' => [
            'class' => 'app\modules\gpio\Module',
        ],
        'pwm' => [
            'class' => 'app\modules\pwm\Module',
        ],
        'ws' => [
            'class' => 'app\modules\ws\Module',
        ],
        'dht' => [
            'class' => 'app\modules\dht\Module',
        ],
        'gamepad' => [
            'class' => 'app\modules\gamepad\Module',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
