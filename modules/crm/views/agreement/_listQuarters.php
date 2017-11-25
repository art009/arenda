<?php
/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

use yii\helpers\Html;
$this->registerJsFile('/script/agreement.js',[
    'depends' => [
        'yii\web\JqueryAsset',
    ],
]);
?>

<p class="h3">Информация о помещениях:</p>

<table class="table table-bordered">
    <tr>
        <th>Адрес</th>
        <th>Номер</th>
        <th>Площадь</th>
        <th width="20">Удалить</th>
    </tr>
    <?php foreach($model->quarters as $quarter):?>
        <tr>
            <td><?=$quarter->building->fullAddress?></td>
            <td><?=$quarter->number_room.'('.$quarter->number_bti.')'?></td>
            <td><?=$quarter->area_room?></td>
            <td width="20"><?=Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    Yii::$app->urlManager->createUrl(['/crm/agreement/delete-quarter','agreement' => $model->id,'quarter' => $quarter->id]),
                    [
                        'title' => 'Удалить',
                        'aria-label' => 'Удалить',
                        'onclick' => 'rmQuarter(this,event)',
                    ]
                )?></td>
        </tr>
    <?php endforeach;?>
</table>