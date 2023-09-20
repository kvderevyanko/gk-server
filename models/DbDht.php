<?php

namespace app\models;

use PHPUnit\Exception;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * This is the model class for table "dht".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int $pin
 * @property bool|null $active
 * @property bool|null $home
 */
class DbDht extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dht';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'pin'], 'required'],
            [['deviceId', 'pin'], 'integer'],
            [['pin'], 'integer', 'min' => 1, 'max' => 12],
            [['active', 'home'], 'boolean'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Имя',
            'pin' => 'Пин',
            'active' => 'Активно',
            'home' => 'На главной',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Device::className(), ['id' => 'deviceId']);
    }

}
