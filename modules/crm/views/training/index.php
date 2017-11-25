<?php

use app\models\Training;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Training */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обучение';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/script/training.js',[
    'depends' => 'yii\web\JqueryAsset',
]);
?>
<div class="training-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if (Yii::$app->user->can(\app\models\Training::ROLE_TRAINING_CONTROLL)):?>
        <p>
            <?= Html::a('Новый ролик обучения', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif;?>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'format' => 'raw',
                    'value' => function($data) {
                        return Html::a(
                            $data->title,
                            '#',
                            [
                                'onclick' => 'startShowTraining(this,event)',
                                'data-scr' => $data->video,
                                'data-title' => $data->title,
                            ]
                        );
                    },
                ],
                'description:ntext',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'visible' => Yii::$app->user->can(Training::ROLE_TRAINING_CONTROLL),
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?=$this->render('_modal')?>