<?php

namespace app\modules\gpio\models;

use app\models\Device;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "gpio".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int $pin
 * @property bool|null $value
 * @property bool|null $active
 * @property bool|null $home
 * @property bool|null $motor
 * @property-read ActiveQuery $device
 * @property bool|null $negative
 */
class DbGpio extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'gpio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin'], 'integer'],
            [['value', 'active', 'home', 'motor', 'negative'], 'boolean'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Имя',
            'pin' => 'Пин',
            'value' => 'Значение',
            'active' => 'Активно',
            'home' => 'На главной',
            'motor' => 'Мотор',
            'negative' => 'Негатив',
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
