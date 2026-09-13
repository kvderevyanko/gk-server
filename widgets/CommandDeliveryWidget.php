<?php

namespace app\widgets;

use app\models\CommandDelivery;
use Yii;
use yii\base\Widget;

class CommandDeliveryWidget extends Widget
{
    public $deviceId;
    public $limit = 8;

    public function run(): string
    {
        try {
            $deliveries = CommandDelivery::find()
                ->where(['deviceId' => (int) $this->deviceId])
                ->orderBy(['id' => SORT_DESC])
                ->limit((int) $this->limit)
                ->all();
        } catch (\Throwable $exception) {
            Yii::warning($exception, __METHOD__);

            return '';
        }

        return $this->render('command-delivery', [
            'deliveries' => $deliveries,
        ]);
    }
}
