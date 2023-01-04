<?php


namespace app\modules\ssd1306\assets;

use yii\web\AssetBundle;


class DrawAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/ssd1306/assets/';

    public $css = [
        'css/draw.css',
    ];
    public $js = [
        'js/draw.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AppAsset',
    ];
}
