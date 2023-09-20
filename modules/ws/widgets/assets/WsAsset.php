<?php


namespace app\modules\ws\widgets\assets;

use yii\web\AssetBundle;

class WsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/ws/widgets/assets/';


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
