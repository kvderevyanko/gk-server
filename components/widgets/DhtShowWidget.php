<?php


namespace app\components\widgets;


use app\models\Dht;
use app\models\PwmValues;
use yii\base\Widget;

class DhtShowWidget extends Widget
{
    public $deviceId;
    public $mainPage;

    public function run()
    {

        $dhts = Dht::find()->where(['active' => Dht::STATUS_ACTIVE]);
        if($this->deviceId)
            $dhts->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $dhts->andWhere(['home' => true]);

        $dhts = $dhts->all();

        if(count($dhts) < 1)
            return '';

        return $this->render('dht-show', ['dhts' => $dhts]);
    }
}