<?php

use app\modules\dht\models\Dht;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dhtList array */
/* @var $dht Dht */

?>


    <div class="col-sm-12">

        <h4>Температура</h4>
        <?php foreach ($dhtList as $dht): ?>
            <div class="row">
                <div class="col-md-4">
                    <div id="dht_block_<?= $dht->deviceId ?>_<?= $dht->pin ?>">
                        <label><?= $dht->name ?> / <?= $dht->device->name ?></label>
                        <br>
                        <span class="dhtInfo">
                            <?php
                            echo $this->render('_dht-show-temperature',
                                ['lastTemperature' => $dht->lastTemperature($dht->pin)])
                            ?>
                        </span>
                        <br> <br>
                        <span class="btn btn-info dht-button"
                              data-device="<?= $dht->deviceId ?>"
                              data-pin="<?= $dht->pin ?>"
                        >Обновить температуру</span><br>
                        <br>
                        <span class="btn btn-success dht-graph"
                              data-device="<?= $dht->deviceId ?>"
                              data-pin="<?= $dht->pin ?>"
                        >Показать график</span><br>
                    </div>
                </div>
                <div class="col-md-8">
                    <canvas class="acquisitions" id="acquisitions_<?= $dht->deviceId ?>_<?= $dht->pin ?>"></canvas>
                </div>
            </div>


            <hr>
        <?php endforeach; ?>
    </div>
    <script>
      let urlCommandDht = "<?=Url::to(['/dht/request/get-temperature'])?>"
      let urlGetGraphInfo = "<?=Url::to(['/dht/request/get-graph-info'])?>"
    </script>
<?php
$this->registerCss(<<<CSS
.acquisitions {
        max-height: 300px;
    }
CSS
);