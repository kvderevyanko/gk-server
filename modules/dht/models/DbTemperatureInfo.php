<?php

namespace app\modules\dht\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "temperature_info".
 *
 * @property int $id
 * @property int $deviceId
 * @property int $pin
 * @property float|null $temperature
 * @property float|null $humidity
 * @property int|null $datetime
 */
class DbTemperatureInfo extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'temperature_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'datetime', 'pin'], 'integer'],
            [['temperature', 'humidity'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'deviceId' => 'Device ID',
            'pin' => 'Pin',
            'temperature' => 'Temperature',
            'humidity' => 'Humidity',
            'datetime' => 'Datetime',
        ];
    }
}
