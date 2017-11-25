<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Building */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Строения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <p>
        <?= Html::a('Новое строение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

    //            'id',
                [
                    'attribute'=>'address',
                    'header'=>'Адрес',
//                    'headerOptions' => ['width' => '80'],
                    'value' => function($data){
                        return $data->fullAddress;
                    }
                ],
                [
                    'header'=>'Этажей',
                    'value' => function($data){
                        return $data->countFloors;
                    }
                ],
                [
                    'attribute'=>'updated_at',
                    'value'=> function($data){return date('d.m.Y H:i',$data->updated_at);}
                ],
                [
                    'attribute'=>'created_by',
                    'value'=> function($data){return $data->creater->username;}
                ],
//                'city',
//                'street',
//                'house',
//                'building',
                // 'letter',
                // 'coordinates',
                // 'created_at',
                // 'updated_at',
                // 'created_by',
                // 'updated_by',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{list_floor} {update} {delete}',
                    'buttons' => [
                        'list_floor' => function ($url,$model,$key) {
                            $url = Yii::$app->urlManager->createUrl([ '/crm/floor' ,'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-list-alt"></span>',
                                $url,
                                [
                                    'data-pjax' => 0,
                                    'aria-label' => 'Список этажей',
                                    'title' => 'Список этажей',
                                ]
                            );
                        },

                    ],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
