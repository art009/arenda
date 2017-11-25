<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Room */
?>

<p class="h3">Информация о помещение</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
//        'id',
        'number_room',
        'number_bti',
        [
            'attribute' => 'area_room',
            'format' => 'html',
            'value' => $model->area_room . ' м <sup><small>2</small></sup>',
        ],
//        'floor',
//        'coordinates',
        'comment',
        [
            'attribute' => 'created_at',
            'value' => date('d.m.Y H:i',$model->created_at),
        ],
        [
            'attribute' => 'updated_at',
            'value' => date('d.m.Y H:i',$model->updated_at),
        ],
        [
            'attribute' => 'created_by',
            'value' => ($model->creater)?$model->creater->username:'',
        ],
        [
            'attribute' => 'updated_by',
            'value' => ($model->updater)?$model->updater->username:'',
        ],
    ],
]) ?>
<?php if (Yii::$app->user->can(\app\models\Room::ROLE_ROOM_UPDATE)):?>
    <div class="well well-sm">
        <?=Html::a('<span class="glyphicon glyphicon-pencil"></span> Изменить данные',['/crm/floor/map','id' => $model->floor],['class'=>'btn btn-info'])?>
    </div>
<?php endif;?>
