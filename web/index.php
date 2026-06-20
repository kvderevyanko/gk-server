<?php

$environment = getenv('YII_ENV');
$debug = getenv('YII_DEBUG');

defined('YII_ENV') or define(
    'YII_ENV',
    $environment !== false && $environment !== '' ? $environment : 'prod'
);
defined('YII_DEBUG') or define(
    'YII_DEBUG',
    in_array(strtolower((string) $debug), ['1', 'true', 'yes', 'on'], true)
);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
