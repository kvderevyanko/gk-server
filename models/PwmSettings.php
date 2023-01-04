<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pwm_settings".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $clock
 * @property int $duty
 *
 * @property Device getDevice()
 */
class PwmSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pwm_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Основное устройство',
            'clock' => 'Clock',
            'duty' => 'Duty',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Device::className(), ['id' => 'deviceId']);
    }
}
