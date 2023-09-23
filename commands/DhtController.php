<?php

namespace app\commands;

use app\models\Device;
use app\modules\dht\models\Dht;
use yii\console\Controller;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

/**
 * Класс для работы с термометрами DHT
 */
class DhtController extends Controller
{

    /**
     * Получение значений с активных термометров по крону
     * @return void
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionIndex():void
    {
        $dhtList = Dht::find()
            ->leftJoin([
                Device::tableName(),
                Dht::tableName().'.deviceId = '.Device::tableName().'.id'
            ])
            ->where([
                Dht::tableName().'.active' => Dht::STATUS_ACTIVE,
                Device::tableName().'.active' => Device::STATUS_ACTIVE,
            ])
            ->all();

        foreach ($dhtList as $dht){
            Dht::sendRequest($dht->deviceId, $dht->pin);
        }
    }
}
