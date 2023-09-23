<?php

namespace app\modules\pwm\models;

use app\models\Device;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "pwm_values".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $pin
 * @property string $name
 * @property int|null $value
 * @property bool|null $active
 * @property bool|null $home
 *
 * @property-read ActiveQuery $device
 * @property-read ActiveQuery $settings
 * @property Device getDevice()
 */
class DbPwmValues extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'pwm_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin', 'value'], 'integer'],
            [['active', 'home'], 'boolean'],
            [['name'], 'string'],
            [['deviceId', 'pin'], 'unique', 'targetAttribute' => ['deviceId', 'pin']],
            [['pin'], 'integer', 'min' => 1, 'max' => 35],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Основное устройство',
            'pin' => 'Пин',
            'name' => 'Имя',
            'value' => 'Значение',
            'active' => 'Активно',
            'home' => 'На главной',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getDevice(): ActiveQuery
    {
        return $this->hasOne(Device::className(), ['id' => 'deviceId']);
    }

}
