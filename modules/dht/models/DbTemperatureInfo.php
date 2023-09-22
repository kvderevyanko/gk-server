<?php

namespace app\modules\dht\models;

/**
 * This is the model class for table "temperature_info".
 *
 * @property int $id
 * @property int $deviceId
 * @property float|null $temperature
 * @property float|null $humidity
 * @property int|null $datetime
 */
class DbTemperatureInfo extends \yii\db\ActiveRecord
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
            [['deviceId'], 'required'],
            [['deviceId', 'datetime'], 'integer'],
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
            'temperature' => 'Temperature',
            'humidity' => 'Humidity',
            'datetime' => 'Datetime',
        ];
    }
}
