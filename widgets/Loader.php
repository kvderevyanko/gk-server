<?php

namespace app\widgets;

use yii\base\Widget;

class Loader extends Widget
{


    public function run()
    {

        return $this->render('device-btn');
    }
}