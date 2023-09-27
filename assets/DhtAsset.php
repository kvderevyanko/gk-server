<?php


namespace app\assets;

use yii\web\AssetBundle;


class DhtAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/';
    public $css = [

    ];
    public $js = [
        'js/dht.js',
    ];
    public $depends = [
        'app\assets\ChartJsAsset',
    ];


}
