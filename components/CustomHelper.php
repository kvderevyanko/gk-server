<?php

namespace app\components;

class CustomHelper
{
    /**
     * Форматирование unixTime в дату
     * @param int $dateTime
     * @return string
     */
    public static function formatDateTime(int $dateTime):string
    {
        return date('d/m H:i:s', $dateTime);
    }
}