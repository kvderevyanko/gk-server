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
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
