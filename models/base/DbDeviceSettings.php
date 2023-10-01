<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "device_settings".
 *
 * @property int $id
 * @property int|null $deviceId
 * @property string|null $type
 */
class DbDeviceSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'device_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['deviceId'], 'integer'],
            [['type'], 'string', 'max' => 255],
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
            'type' => 'Type',
        ];
    }
}
