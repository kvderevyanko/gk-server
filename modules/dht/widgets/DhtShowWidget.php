<?php


namespace app\modules\dht\widgets;


use app\models\Device;
use app\modules\dht\models\Dht;
use yii\base\Widget;

class DhtShowWidget extends Widget
{
    public $deviceId;
    public $mainPage;

    /**
     * @return string
     */
    public function run(): string
    {

        $dhtList = Dht::find()
            ->leftJoin([
                Device::tableName(),
                Dht::tableName().'.deviceId = '.Device::tableName().'.id'
            ])
            ->where([
                Dht::tableName().'.active' => Dht::STATUS_ACTIVE,
                Device::tableName().'.active' => Device::STATUS_ACTIVE,
            ]);
        if($this->deviceId)
            $dhtList->andWhere([Dht::tableName().'.deviceId' => $this->deviceId]);
        if($this->mainPage)
            $dhtList->andWhere([Dht::tableName().'.home' => true]);

        $dhtList = $dhtList->all();

        if(count($dhtList) < 1)
            return '';

        return $this->render('dht-show', ['dhtList' => $dhtList]);
    }
}