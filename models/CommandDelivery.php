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
}
