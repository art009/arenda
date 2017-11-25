<?php
use yii\helpers\Html;

?>


<div class="col-lg-12">
    <h2>Окончание договора</h2>
    <table class="table table-bordered">
        <tr>
            <th>Договор</th>
            <th>Помещение</th>
            <th>Арендатор</th>
            <th>Площадь</th>
        </tr>
        <?php foreach($models as $model):?>
            <tr>
                <td><?='№'.$model->id . ' от ' . date('d.m.Y',$model->date_from) . ' по ' . date('d.m.Y',$model->date_to)?></td>
                <td>
                    <?php foreach($model->quarters as $quarter){
                        echo Html::a(
                            $quarter->building->fullAddress . ', оф.'.$quarter->number_room,
                            ['/crm/agreement/map','id' => $quarter->floor]
                        );
                        echo '<br/>';
                    }?>
                </td>
                <td><?=$model->ten->name?></td>
                <td><?=$model->getAreaAgreement()?></td>
            </tr>
        <?php endforeach;?>
    </table>
</div>