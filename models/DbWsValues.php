<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\httpclient\Client;

/**
 * This is the model class for table "ws_values".
 *
 * @property int $id
 * @property int $deviceId
 * @property string|null $name
 * @property int|null $defaultBuffer
 * @property int|null $buffer
 * @property string|null $mode
 * @property int|null $delay
 * @property int|null $bright
 * @property string|null $singleColor
 * @property string|null $gradientColor
 * @property string|null $modeOptions
 * @property bool|null $active
 * @property bool|null $home
 */
class DbWsValues extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ws_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviceId', 'defaultBuffer'], 'required'],
            [['deviceId'], 'unique'],
            [['deviceId', 'buffer', 'defaultBuffer', 'delay', 'bright'], 'integer'],
            [['delay'], 'integer', 'min' => 4],
            [['bright'], 'integer', 'min' => 0, 'max' => 255],
            [['gradientColor'], 'string'],
            [['active', 'home'], 'boolean'],
            [['name', 'mode', 'singleColor', 'modeOptions'], 'string', 'max' => 255],
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
            'buffer' => 'Количество диодов',
            'defaultBuffer' => 'Количество диодов по умолчанию',
            'mode' => 'Режим',
            'delay' => 'Задержка',
            'bright' => 'Яркость',
            'singleColor' => 'Одиночный цвет',
            'gradientColor' => 'Градиент цветов',
            'modeOptions' => 'Опции режима',
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
