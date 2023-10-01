<?php

namespace app\models\base;

/**
 * Таблица с настройками
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property bool|null $active
 */
class DbSettings extends \yii\db\ActiveRecord
{

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
