<?php

namespace app\commands;

use app\models\base\DbCommands;
use app\models\Device;
use app\modules\gpio\models\Gpio;
use yii\console\Controller;
use yii\helpers\ArrayHelper;


class CommandController extends Controller
{


    /**
     * Базовая команда - считает параметры из настроек и за ё
     * @return void
     */
    public function actionIndex()
    {
        $commands = DbCommands::find()->where([DbCommands::tableName().'.active' => true])
            ->orderBy([DbCommands::tableName().'.conditionSort' => SORT_ASC])
            ->leftJoin(Device::tableName(), Device::tableName().".id = ".DbCommands::tableName().".deviceId")
            ->andWhere([Device::tableName().".active" => Device::STATUS_ACTIVE])
            ->all();
        $commands = ArrayHelper::toArray($commands);
        $commands = ArrayHelper::index($commands, function ($element) {
            return $element['id'];
        });
        //Начинаем разбирать значения по типу: pinType => deviceId => pin

        $result = [];
        foreach ($commands as $command) {
            if(!array_key_exists($command['pinType'], $result))
                $result[$command['pinType']] = [];
            $result[$command['pinType']][$command['id']] = $command;
        }

        foreach ($result as $key=>$pinTypes) {
            $pinTypesResult = [];
            foreach ($pinTypes as $pin) {
                if(!array_key_exists($pin['deviceId'], $pinTypesResult))
                    $pinTypesResult[$pin['deviceId']] = [];
                $pinTypesResult[$pin['deviceId']][$pin['id']] = $pin;
            }
            $result[$key] = $pinTypesResult;
        }

        foreach ($result as $keyPinTypes=>$pinTypes) {
            $deviceResult = [];
            foreach ($pinTypes as $keyDeviceId=>$deviceId) {
                $pinResult = [];
                foreach ($deviceId as $pin) {
                    if(!array_key_exists($pin['pin'], $pinResult))
                        $pinResult[$pin['pin']] = [];
                    $pinResult[$pin['pin']][$pin['id']] = $pin;
                }
                $deviceResult[$keyDeviceId] = $pinResult;
            }
            $result[$keyPinTypes] = $deviceResult;
        }

        //Разбили по структуре  pinType => deviceId => pin, теперь начинаем высчитывать условия
        foreach ($result as $pinType => $device){
            if($pinType === DbCommands::PIN_TYPE_GPIO) {
                foreach ($device as $deviceId => $pins) {
                    foreach ($pins as $pin => $pinValues){
                        $gpio = Gpio::findOne(['deviceId' => $deviceId, 'pin' => $pin, 'active' => Gpio::STATUS_ACTIVE]);
                        if($gpio) {
                            $value = $gpio->value;
                            $h = date('H', time()); //Сколько сейчас часов
                           // echo "\n".$h."\n";
                            //echo "\n". date('d.m.Y H:i:s', time())."\n";
                            foreach ($pinValues as $values) {
                                if((int)$values['conditionFrom'] <= $h && $h < (int)$values['conditionTo']) {
                                    $value = $values['pinValue'];
                                }

                            }
                            $gpio->value = $value;
                            $gpio->save();
                        }
                    }
                    try {
                        Gpio::sendRequest($deviceId);
                    } catch (\Exception $e) {
                    }
                }
            }
        }
    }

}
