<?php

namespace app\models\base;
use app\modules\dht\models\DbDht;
use app\modules\dht\models\DbTemperatureInfo;
use app\modules\gpio\models\DbGpio;
use app\modules\pwm\models\DbPwmSettings;
use app\modules\pwm\models\DbPwmValues;
use app\modules\ws\models\DbWsValues;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "device".
 *
 * @property int $id
 * @property string|null $name
 * @property string $host
 * @property string $class
 * @property string $icon
 * @property bool|null $active
 * @property bool|null $home
 * @property string $type
 */
class DbDevice extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['host'], 'required'],
            [['active', 'home'], 'boolean'],
            [['name', 'host', 'class', 'type', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'host' => 'Хост',
            'type' => 'Тип',
            'active' => 'Активно',
            'icon' => 'Имя иконки',
            'class' => 'Класс кнопки',
            'home' => 'На главной',
        ];
    }

    public static function btnClass(): array
    {
        return [
            'btn-default' => 'btn-default',
            'btn-primary' => 'btn-primary',
            'btn-success' => 'btn-success',
            'btn-info' => 'btn-info',
            'btn-warning' => 'btn-warning',
            'btn-danger' => 'btn-danger',
            'btn-link' => 'btn-link',
        ];
    }


    /**
     * @return void
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function afterDelete(): void
    {
        $pwmSettings = DbPwmSettings::findAll(['deviceId' => $this->id]);
        foreach ($pwmSettings as $settings)
            $settings->delete();

        $pwmValues = DbPwmValues::findAll(['deviceId' => $this->id]);
        foreach ($pwmValues as $values)
            $values->delete();

        $wsValues = DbWsValues::findAll(['deviceId' => $this->id]);
        foreach ($wsValues as $ws)
            $ws->delete();

        $gpioValues = DbGpio::findAll(['deviceId' => $this->id]);
        foreach ($gpioValues as $gpio)
            $gpio->delete();

        $dhtDevices = DbDht::findAll(['deviceId' => $this->id]);
        foreach ($dhtDevices as $dht)
            $dht->delete();

        DbTemperatureInfo::deleteAll(['deviceId' => $this->id]);

        DbCommands::deleteAll(['deviceId' => $this->id]);

        parent::afterDelete();
    }
}
