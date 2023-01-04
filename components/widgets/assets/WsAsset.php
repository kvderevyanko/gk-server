<?php


namespace app\components\widgets\assets;

use yii\web\AssetBundle;

class WsAsset extends AssetBundle
{
    public $sourcePath = '@app/components/widgets/assets/';


    public $css = [
        'css/ws.css',

    ];
    public $js = [
        'js/ws.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
