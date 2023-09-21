<?php

namespace app\modules\pwm\models;

use app\components\EspRequest\EspRequest;
use app\models\Device;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "pwm_values".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $pinId
 * @property string $name
 * @property int|null $value
 * @property bool|null $active
 * @property bool|null $home
 *
 * @property Device getDevice()
 */
class PwmValues extends DbPwmValues
{


    /**
     * @return ActiveQuery
     */
    public function getSettings(): ActiveQuery
    {
        return $this->hasOne(PwmSettings::className(), ['deviceId' => 'deviceId']);
    }

    /**
     * @param $deviceId
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws Exception
     */
    public static function sendRequest($deviceId): string
    {
        $pwm = self::findAll(['deviceId' => $deviceId, 'active' => self::STATUS_ACTIVE]);
        $params = ArrayHelper::map($pwm, 'pinId', 'value');
        $pwmSettings = PwmSettings::findOne(['deviceId' => $deviceId]);
        if($pwmSettings === null)
            throw new NotFoundHttpException("Настройки PWM не найдены");

        $device = Device::getActiveDevice($deviceId);
        $params['clock'] = $pwmSettings->clock;
        $params['duty'] = $pwmSettings->duty;
        return (new EspRequest($device->host,'gpio-pwm.lc', $params))->send();
    }
}
