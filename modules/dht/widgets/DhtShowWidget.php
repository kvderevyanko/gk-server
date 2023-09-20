<?php


namespace app\modules\dht\widgets;


use app\models\DbDht;
use yii\base\Widget;

class DhtShowWidget extends Widget
{
    public $deviceId;
    public $mainPage;

    public function run()
    {

        $dhts = DbDht::find()->where(['active' => DbDht::STATUS_ACTIVE]);
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