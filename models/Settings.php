<?php

namespace app\models;

use Yii;

/**
 * Таблица с настройками
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property bool|null $active
 */
class Settings extends \yii\db\ActiveRecord
{

    const MOTOR_INTERVAL = 'motor_interval';
    const SITE_NAME = 'site_name';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['key'], 'required'],
            [['active'], 'boolean'],
            [['key', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'active' => 'Active',
        ];
    }
}
