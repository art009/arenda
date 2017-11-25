<?php

use yii\helpers\Html;
use app\modules\crm\components\SummerAsset;
use app\modules\crm\components\MapAreaDrawAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Floor */
/* @var $image app\models\forms\UploadFile */

$this->title = 'Карта этажа: ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => 'Здания', 'url' => ['/crm/building']];
$this->params['breadcrumbs'][] = ['label' => 'Этажи', 'url' => ['index','id' =>  $model->building]];
$this->params['breadcrumbs'][] = $this->title;

//SummerAsset::register($this);
?>
<div class="floor-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php MapAreaDrawAsset::register($this); ?>

    <div class="row">
        <div class="col-lg-9">

            <div class="preview" id="preview">
                <div class="inner" id="draw">
                    <?=Html::img(
                        $model->showMap,
                        [
//                            'class' => 'img-responsive',
                            'alt'=>'Sample',
                        ]
                    )?>
                    <canvas id="canvas"></canvas>
                    <div class="points" id="points"></div>
                </div>
            </div>

            <div class="bar" id="bar"></div>

            <div class="info" id="info"></div>

        </div>
        <div class="col-lg-3">

        </div>
    </div>

</div>
