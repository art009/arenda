<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TypeContact */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы контактов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <p>
        <?= Html::a('Новый тип контакта', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
//            'parametr',
//            'created_at',
//            'updated_at',
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
