<?php

namespace app\modules\dht\models;

use app\models\Device;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "dht".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int $pin
 * @property bool|null $active
 * @property-read ActiveQuery $device
 * @property bool|null $home
 */
class DbDht extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'dht';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin'], 'integer'],
            [['deviceId', 'pin'], 'unique', 'targetAttribute' => ['deviceId', 'pin']],
            [['pin'], 'integer', 'min' => 1, 'max' => 12],
            [['active', 'home'], 'boolean'],
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
