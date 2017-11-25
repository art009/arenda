<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\AutoComplete;// Указываете путь до библиотеки
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Agreement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список договоров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <p>
        <?php // Html::a('Добавить договор', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'value' => function ($data){
                    return '#'.$data->id;
                },
                'headerOptions' => [
                    'width' => '20px',
                ],
            ],
            [
                'attribute'=>'inner_number',
                'value'=> function($data){return ($data->inner_number)?$data->inner_number:$data->id.' от '.date('d.m.Y',$data->agreement_date);}
            ],
            [
                'attribute' => 'tenant',
                'value' => function($data){
                    return $data->ten->name;
                },
            ],
            [
                'attribute' => 'date_from',
                'value' => function($data){return date('d.m.Y',$data->date_from);},
            ],
            [
                'attribute' => 'date_to',
                'value' => function($data){return date('d.m.Y',$data->date_to);},
            ],
            /*
            [
                'header'=>'Период',
                'value'=> function($data){return date('d.m.Y',$data->date_from) .' - '. date('d.m.Y',$data->date_to);}
            ],


            [
                'header' => 'Адрес',
                'attribute' => 'building',
                'value' => function($data){
                    return $data->build->fullAddress;
                },
                'filter' => \app\models\Building::getArrayBuilding(),
            ],
            [
                'attribute' => 'quarters',
                'value' => function($data){
                    return 'оф.№'.$data->quarter->number_room;
                }
            ],
            */
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            [
                'attribute'=>'created_at',
                'value'=> function($data){return date('d.m.Y H:i',$data->created_at);}
            ],
            [
                'attribute'=>'created_by',
                'value'=> function($data){return $data->creater->username;}
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
