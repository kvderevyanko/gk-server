<?php

namespace app\modules\pwm\models;

use app\models\Device;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "pwm_settings".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $clock
 * @property int $duty
 *
 * @property-read ActiveQuery $device
 * @property Device getDevice()
 */
class DbPwmSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'pwm_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'clock', 'duty'], 'required'],
            [['deviceId', 'clock', 'duty'], 'integer'],
            [['deviceId'], 'unique'],
            [['clock'], 'integer', 'max' => 40000, 'min' => 0],
            [['duty'], 'integer', 'max' => 1023, 'min' => 0],
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
            'clock' => 'Clock',
            'duty' => 'Duty',
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
