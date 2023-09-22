<?php
namespace app\modules\pwm\controllers;

use app\modules\pwm\models\PwmValues;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PwmController extends Controller
{
    /**
     * @param $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionRequest($deviceId): string
    {
        $request = \Yii::$app->request->get();
        $pwmList = PwmValues::findAll(['deviceId' => $deviceId]);
        foreach ($pwmList as $pwm) {
            if(array_key_exists($pwm->id, $request)){
                $pwm->value = $request[$pwm->id];
                $pwm->save();
            }
        }
        return PwmValues::sendRequest($deviceId);
    }
}
