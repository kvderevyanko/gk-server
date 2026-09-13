<?php

namespace app\controllers;

use app\models\Device;
use app\modules\dht\models\Dht;
use app\modules\dht\models\TemperatureInfo;
use app\modules\gpio\models\Gpio;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index', $this->overviewData());
    }

    /**
     * Builds the dashboard from saved data only. The overview must not poll or
     * otherwise contact ESP devices merely because a page was opened.
     *
     * @return array
     */
    private function overviewData(): array
    {
        $devices = Device::find()
            ->where(['active' => Device::STATUS_ACTIVE, 'home' => true])
            ->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        $deviceIds = array_map(static function (Device $device): int {
            return (int) $device->id;
        }, $devices);

        if ($deviceIds === []) {
            return ['deviceCards' => [], 'sensors' => []];
        }

        $gpioCounts = [];
        foreach (Gpio::find()
            ->select(['deviceId', 'total' => 'COUNT(*)'])
            ->where(['deviceId' => $deviceIds, 'active' => Gpio::STATUS_ACTIVE])
            ->groupBy(['deviceId'])
            ->asArray()
            ->all() as $count) {
            $gpioCounts[(int) $count['deviceId']] = (int) $count['total'];
        }

        $dhtList = Dht::find()
            ->with('device')
            ->where([
                'deviceId' => $deviceIds,
                'active' => Dht::STATUS_ACTIVE,
                'home' => true,
            ])
            ->orderBy(['deviceId' => SORT_ASC, 'pin' => SORT_ASC])
            ->all();
        $sensorCounts = [];
        foreach ($dhtList as $dht) {
            $sensorCounts[(int) $dht->deviceId] = ($sensorCounts[(int) $dht->deviceId] ?? 0) + 1;
        }

        $latestReadingIds = TemperatureInfo::find()
            ->select('MAX([[id]])')
            ->where(['deviceId' => $deviceIds])
            ->groupBy(['deviceId', 'pin'])
            ->column();
        $readingsBySensor = [];
        if ($latestReadingIds !== []) {
            foreach (TemperatureInfo::find()->where(['id' => $latestReadingIds])->all() as $reading) {
                $readingsBySensor[$reading->deviceId . ':' . $reading->pin] = $reading;
            }
        }

        $deviceCards = [];
        foreach ($devices as $device) {
            $deviceCards[] = [
                'device' => $device,
                'gpioCount' => $gpioCounts[(int) $device->id] ?? 0,
                'sensorCount' => $sensorCounts[(int) $device->id] ?? 0,
            ];
        }

        $sensors = [];
        foreach ($dhtList as $dht) {
            $sensors[] = [
                'sensor' => $dht,
                'reading' => $readingsBySensor[$dht->deviceId . ':' . $dht->pin] ?? null,
            ];
        }

        return compact('deviceCards', 'sensors');
    }


    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionTest(){
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('http://192.168.1.35/rc-block')
            ->setData([
                'a' => 1,
                'b' =>2,
                'ce' => 'hello222'
            ])
            ->setOptions([
                'timeout' => 2, // set timeout to 5 seconds for the case server is not responding
            ])
            ->send();
        if ($response->isOk) {
            echo $response->content;
        } else {
            echo 'Нет ответа';
        }
        exit;
    }
}
