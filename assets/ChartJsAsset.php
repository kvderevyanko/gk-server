<?php


namespace app\assets;

use yii\web\AssetBundle;


class ChartJsAsset extends AssetBundle
{
    public $sourcePath = '@npm/chart.js';
    public $css = [

    ];
    public $js = [
        'dist/chart.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];


}
