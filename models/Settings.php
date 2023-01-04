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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'active' => 'Active',
        ];
    }
}
