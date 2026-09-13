<?php


namespace app\modules\ws\widgets;

use app\modules\ws\models\WsValues;
use app\models\Device;
use yii\base\Widget;

class WsShowWidget extends Widget
{
    public  $deviceId;
    public  $mainPage;

    public function run(): string
    {
        $wsValues = WsValues::find()
            ->innerJoinWith('device')
            ->where([
                WsValues::tableName() . '.active' => WsValues::STATUS_ACTIVE,
                Device::tableName() . '.active' => Device::STATUS_ACTIVE,
            ])
            ->orderBy([WsValues::tableName() . '.id' => SORT_ASC]);
        if($this->deviceId)
            $wsValues->andWhere(['deviceId' => $this->deviceId]);
        if($this->mainPage)
            $wsValues->andWhere(['home' => true]);
        $wsValues = $wsValues->with('device')->all();

        if(count($wsValues) < 1)
            return '';

        return $this->render('ws-show', ['wsValues' => $wsValues]);
    }
}
