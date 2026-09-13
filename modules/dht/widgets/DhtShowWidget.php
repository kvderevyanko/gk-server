<?php


namespace app\modules\dht\widgets;



use app\assets\DhtAsset;
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

        DhtAsset::register($this->getView());
        $dhtList = Dht::find()
            ->innerJoinWith('device')
            ->where([
                Dht::tableName().'.active' => Dht::STATUS_ACTIVE,
                Device::tableName().'.active' => Device::STATUS_ACTIVE,
            ])
            ->orderBy([Dht::tableName() . '.id' => SORT_ASC]);
        if($this->deviceId)
            $dhtList->andWhere([Dht::tableName().'.deviceId' => $this->deviceId]);
        if($this->mainPage)
            $dhtList->andWhere([Dht::tableName().'.home' => true]);

        $dhtList = $dhtList->with('device')->all();

        if(count($dhtList) < 1)
            return '';

        return $this->render('dht-show', ['dhtList' => $dhtList]);
    }
}
