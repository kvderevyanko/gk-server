<?php


namespace app\modules\ws\widgets;

use app\modules\ws\models\WsValues;
use yii\base\Widget;

class WsShowWidget extends Widget
{
    public  $deviceId;
    public  $mainPage;

    public function run()
    {
        $wsValues = WsValues::find()->where(['active' => WsValues::STATUS_ACTIVE]);
        if($this->deviceId)
            $wsValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $wsValues->andWhere(['home' => true]);
        $wsValues = $wsValues->all();

        if(count($wsValues) < 1)
            return '';

        return $this->render('ws-show', ['wsValues' => $wsValues]);
    }
}