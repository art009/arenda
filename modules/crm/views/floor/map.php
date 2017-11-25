<?php

use yii\helpers\Html;
//use app\modules\crm\components\SummerAsset;
use app\modules\crm\components\MapAreaDrawAsset;
use app\modules\crm\components\HighlightingMapAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Floor */
/* @var $image app\models\forms\UploadFile */

$this->title = 'Карта этажа: ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => 'Здания', 'url' => ['/crm/building']];
$this->params['breadcrumbs'][] = ['label' => 'Этажи', 'url' => ['index','id' =>  $model->building]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/script/map.js',[
    'depends' => [
        'app\modules\crm\components\HighlightingMapAsset',
//        'yii\web\JqueryAsset',
    ],
]);

//SummerAsset::register($this);
MapAreaDrawAsset::register($this);
//HighlightingMapAsset::register($this);
?>
<div class="floor-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= app\modules\crm\widgets\FloorsBtn\FloorsBtn::widget(['build' => $model->building])?>

    <div class="preview" id="preview">
        <div class="inner" id="draw">
            <?=Html::img(
                $model->showMap,
                [
                    'class' => 'map',
                    'alt'=>Html::encode($this->title),
                    'usemap' => '#features',
                ]
            )?>

            <canvas class="hidden" id="canvas"></canvas>
            <div class="hidden points" id="points"></div>
        </div>
    </div>

    <div class="bar" id="bar"></div>

    <textarea class="info" id="info" rows="3" style="width:100%;" data-href="<?= Yii::$app->urlManager->createUrl(['/crm/room/point-coordinate','floor' => $model->id]);?>"></textarea>
</div>

<map name="features">
    <?php foreach($model->coordinates as $coordinate):?>
        <?php if($coordinate->room == NULL): ?>
            <area shape="poly" coords="<?=$coordinate->coordinates;?>" href="<?=Yii::$app->urlManager->createUrl(['/crm/room/create','coordinate' => $coordinate->id,'floor' => $coordinate->floor])?>" onClick="return modifRoom(this,event)" data-maphilight='{"fillColor":"00ff00","shadow":true,"shadowBackground":"ffffff","alwaysOn":true}'>
        <?php else: ?>
            <area shape="poly" coords="<?=$coordinate->coordinates;?>" href="<?=Yii::$app->urlManager->createUrl(['/crm/room/update','id' => $coordinate->room->id])?>" onClick="return modifRoom(this,event)" data-maphilight='{"fillColor":"0011FF","shadow":true,"shadowBackground":"ffffff","alwaysOn":true}'>
        <?php endif;?>
    <?php endforeach;?>
</map>

<div class="coordinate-file">
    <?=Html::beginForm(['/crm/room/coordinates'],'POST',['enctype' => 'multipart/form-data'])?>
        <?=Html::hiddenInput('floor',$model->id);?>
        <?=Html::fileInput('file');?>
        <?=Html::submitButton('Загрузить',['class' => 'btn btn-primary']);?>
    <?=Html::endForm()?>
</div>

<?=$this->render('_modal');?>

