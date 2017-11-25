<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Building;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Floor */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $buildingModel app\models\Building */

$this->title = 'Этажи здания: '.$buildingModel->fullAddress;
$this->params['breadcrumbs'][] = ['label' => 'Строения', 'url' => ['/crm/building/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="floor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <p>
        <?= Html::a('Добавить этаж', ['create', 'building' => $buildingModel->id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'label',
            [
                'attribute' => 'building',
                'value' => function($data){return $data->build->fullAddress;},
                'filter' => false,//Building::getArrayBuilding(),
            ],
//            'map',
//            'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            [
                'attribute'=>'updated_at',
                'value'=> function($data){return date('d.m.Y H:i',$data->updated_at);},
                'filter' => false,
            ],
            [
                'attribute'=>'created_by',
                'value'=> function($data){return $data->creater->username;},
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{map} {update} {delete}',
                'buttons' => [
                    'map' => function($url,$model,$key){
                        $url = Yii::$app->urlManager->createUrl(['/crm/floor/map','id' => $model->id]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-globe"></span>',
                            $url,
                            [
                                'data-pjax' => 0,
                                'aria-label' => 'Карта этажа',
                                'title' => 'Карта этажа',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
</div>
