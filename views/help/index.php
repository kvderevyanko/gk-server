<?php

use yii\helpers\Html;
use \yii\helpers\Markdown;

/* @var $this yii\web\View */
/* @var $html string */


$this->title = 'Справка';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/help/index']];
?>
    <div id="helpBlock">
        <?= $html ?>
    </div>
    <script>
        const urlGetDoc = "<?=\yii\helpers\Url::to(['get-doc'])?>";
    </script>
<?php
$this->registerCss(<<<CSS
#helpBlock img {
    max-width: 90%;
    margin: 10px;
}
CSS
);