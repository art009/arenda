<?php

use yii\helpers\Html;
//use app\modules\crm\components\SummerAsset;
use app\modules\crm\components\MapAreaDrawAsset;
use app\modules\crm\components\HighlightingMapAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Floor */
/* @var $image app\models\forms\UploadFile */

$this->title = 'Карта этажа: ' . $model->label;
//$this->params['breadcrumbs'][] = ['label' => 'Договоры', 'url' => ['/crm/agreement']];
//$this->params['breadcrumbs'][] = ['label' => 'Этажи', 'url' => ['index','id' =>  $model->building]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/script/map.js',[
    'depends' => [
        'app\modules\crm\components\HighlightingMapAsset',
//        'yii\web\JqueryAsset',
    ],
]);

$this->registerJsFile('/script/agreement.js',[
    'depends' => [
        'yii\web\JqueryAsset',
    ],
]);

//SummerAsset::register($this);
//MapAreaDrawAsset::register($this);
//HighlightingMapAsset::register($this);
?>
<div class="floor-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= app\modules\crm\widgets\FloorsBtn\FloorsBtn::widget(['build' => $model->building])?>

    <?=Html::img(
        $model->showMap,
        [
//            'class' => 'map img-responsive',
            'class' => 'map',
            'alt'=>Html::encode($this->title),
            'usemap' => '#features',
        ]
    )?>
</div>

<map name="features">
    <?php foreach($model->coordinates as $coordinate):?>
        <?php if($coordinate->room == NULL)     continue;?>

        <?php $color = 'FF0000';
            if ($coordinate->activeAgreement())
                $color = '00FF00';
            if ($coordinate->warningAgreement())
                $color = 'FFFF00';
        ?>

        <?php
            $dataLegal = '';
            if ($count = $coordinate->hasCountActiveLegal()){
                $dataLegal = 'data-legal = '.$count;
            }
        ?>

        <?php
            if (Yii::$app->user->can(\app\models\Agreement::ROLE_AGREEMENT_INFO)) {
                $url = Yii::$app->urlManager->createUrl(['/crm/agreement/info', 'quarters' => $coordinate->room->id]);
                $onclick = 'return showInform(this,event)';
            } else {
                $url = '#';
                $onclick = 'return false';
            }
        ?>

        <area shape="poly" <?=$dataLegal?> coords="<?=$coordinate->coordinates;?>" href="<?=$url?>" onClick="<?=$onclick?>" data-maphilight='{"fillColor":"<?=$color?>","shadow":true,"shadowBackground":"ffffff","alwaysOn":true}'>

    <?php endforeach;?>
</map>

<?=$this->render('/floor/_modal');?>

