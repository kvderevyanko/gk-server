<?php


use app\modules\dht\models\Dht;
use yii\web\View;

/* @var $this View */
/* @var $lastTemperature object */

?>
<?php
if ($lastTemperature) {
    echo date('d/m H:i:s', $lastTemperature->datetime);
    if ($lastTemperature->temperature)
        echo ' Температура: ' . $lastTemperature->temperature . ', ';

    if ($lastTemperature->humidity)
        echo ' Влажность: ' . $lastTemperature->humidity;
}
?>