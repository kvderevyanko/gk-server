<?php

namespace app\modules\ssd1306\models;

use Yii;

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
class Ssd1306Images extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ssd1306_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
