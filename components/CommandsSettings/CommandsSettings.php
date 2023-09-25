<?php
namespace app\components\CommandsSettings;
/**
 * Работа с командами пинов
 */
abstract class CommandsSettings implements CommandsSettingsInterface {
   private int $deviceId;
   private string $pinType;
   private int $pin;
   private string $conditionType;
   private int $conditionFrom;
   private int $conditionTo;
   private int $pinValue;
   private int $conditionSort;
   private bool $active;

    public function __construct(
         int $deviceId,
         string $pinType,
         int $pin,
         string $conditionType,
         int $conditionFrom,
         int $conditionTo,
         int $pinValue,
         int $conditionSort,
         bool $active
    ) {
        $this->deviceId = $deviceId;
        $this->pinType = $pinType;
        $this->pin = $pin;
        $this->conditionType = $conditionType;
        $this->conditionFrom = $conditionFrom;
        $this->conditionTo = $conditionTo;
        $this->pinValue = $pinValue;
        $this->conditionSort = $conditionSort;
        $this->active = $active;
    }

}