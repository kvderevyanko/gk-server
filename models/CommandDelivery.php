<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Immutable record of the last attempt to deliver a command to an ESP device.
 * Desired peripheral state stays in its own table; this model records only
 * whether the device confirmed a particular attempt.
 *
 * @property int $id
 * @property int $deviceId
 * @property string $type
 * @property int|null $pin
 * @property string $status
 * @property string $message
 * @property string|null $payload
 * @property int $createdAt
 */
class CommandDelivery extends ActiveRecord
{
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_ERROR = 'error';

    public static function tableName(): string
    {
        return 'command_delivery';
    }

    public static function record(
        int $deviceId,
        string $type,
        string $status,
        string $message,
        ?int $pin = null,
        array $payload = []
    ): void {
        try {
            $record = new self([
                'deviceId' => $deviceId,
                'type' => $type,
                'pin' => $pin,
                'status' => $status,
                'message' => $message,
                'payload' => $payload ? Json::encode($payload) : null,
                'createdAt' => time(),
            ]);

            if (!$record->save(false)) {
                Yii::warning('Не удалось сохранить журнал доставки команды.', __METHOD__);
            }
        } catch (\Throwable $exception) {
            // Logging must never turn a completed physical command into a failed request.
            Yii::warning($exception, __METHOD__);
        }
    }

    public static function typeLabel(string $type): string
    {
        $labels = [
            'gpio' => 'GPIO',
            'pwm' => 'PWM',
            'dht' => 'DHT',
            'ws' => 'WS2812',
        ];

        return $labels[$type] ?? $type;
    }

    /**
     * Converts stored payload into a short, operator-facing description.
     * The payload is advisory: malformed historic records stay readable.
     */
    public static function description(self $delivery): array
    {
        $payload = self::payloadData($delivery->payload);
        $pin = $delivery->pin === null ? null : 'Пин ' . $delivery->pin;

        switch ($delivery->type) {
            case 'gpio':
                return [
                    'category' => 'Переключатель',
                    'target' => $pin ?: 'GPIO',
                    'command' => array_key_exists('value', $payload) && (bool) $payload['value']
                        ? 'Включить'
                        : 'Выключить',
                ];
            case 'pwm':
                return [
                    'category' => 'Яркость и мощность',
                    'target' => $pin ?: 'PWM',
                    'command' => array_key_exists('value', $payload)
                        ? 'Установить ' . (int) $payload['value'] . ' из 1023'
                        : 'Изменить значение PWM',
                ];
            case 'dht':
                $readings = [];
                if (array_key_exists('temperature', $payload) && $payload['temperature'] !== null) {
                    $readings[] = 'Температура ' . $payload['temperature'] . '°C';
                }
                if (array_key_exists('humidity', $payload) && $payload['humidity'] !== null) {
                    $readings[] = 'Влажность ' . $payload['humidity'] . '%';
                }

                return [
                    'category' => 'Датчик климата',
                    'target' => $pin ?: 'DHT',
                    'command' => $readings ? implode(' · ', $readings) : 'Получить показания',
                ];
            case 'ws':
                $details = [];
                if (!empty($payload['mode'])) {
                    $details[] = self::wsModeLabel((string) $payload['mode']);
                }
                if (array_key_exists('buffer', $payload)) {
                    $details[] = (int) $payload['buffer'] . ' LED';
                }
                if (array_key_exists('bright', $payload)) {
                    $details[] = 'яркость ' . (int) $payload['bright'];
                }

                return [
                    'category' => 'Адресная лента',
                    'target' => 'WS2812',
                    'command' => $details ? implode(' · ', $details) : 'Изменить эффект ленты',
                ];
            default:
                return [
                    'category' => self::typeLabel($delivery->type),
                    'target' => $pin ?: self::typeLabel($delivery->type),
                    'command' => 'Отправить команду',
                ];
        }
    }

    private static function payloadData(?string $payload): array
    {
        if (!$payload) {
            return [];
        }

        try {
            $data = Json::decode($payload);
        } catch (\Throwable $exception) {
            return [];
        }

        return is_array($data) ? $data : [];
    }

    private static function wsModeLabel(string $mode): string
    {
        $labels = [
            'off' => 'Выключить',
            'static' => 'Статичный свет',
            'static-soft-blink' => 'Мягкое мигание',
            'static-soft-random-blink' => 'Случайное мигание',
            'round-static' => 'Статичный круг',
            'round-random' => 'Случайный круг',
            'rainbow' => 'Радуга',
            'rainbow-circle' => 'Радуга по кругу',
        ];

        return $labels[$mode] ?? $mode;
    }
}
