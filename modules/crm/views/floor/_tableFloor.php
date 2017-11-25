<?php
    use yii\helpers\Html;
?>
<table class="table table-bordered">
    <tr>
        <th>Этаж</th>
        <th>Площадь</th>
        <th>Сдано</th>
    </tr>
    <?php foreach($model->floors as $floor):?>
        <tr>
            <td><?=Html::a($floor->label,['/crm/agreement/map','id' => $floor->id])?></td>
            <td><?=$floor->totalArea()?></td>
            <td><?=$floor->rentArea()?></td>
        </tr>
    <?php endforeach;?>
</table>