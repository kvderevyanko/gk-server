<?php

namespace app\modules\ssd1306\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ssd1306_images".
 *
 * @property int $id
 * @property int|null $width
 * @property int|null $height
 * @property int|null $size
 * @property string|null $code
 * @property string|null $html
 */
class Ssd1306Images extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ssd1306_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['width', 'height', 'size'], 'integer'],
            [['code', 'html'], 'string'],
            [['code', 'width', 'height', 'size'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'width' => 'Width',
            'height' => 'Height',
            'size' => 'Size',
            'code' => 'Code',
            'html' => 'Html',
        ];
    }
}
