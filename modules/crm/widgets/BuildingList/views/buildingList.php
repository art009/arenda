<?php
/* @var $this yii\web\View */
/* @var $models app\models\Building */

$this->registerJsFile('/script/building.js');
?>

<div class="row">
    <div class="col-lg-6 col-xs-12">
        <h2>Список объектов</h2>
        <table class="table table-bordered">
            <tr>
                <th>Адрес</th>
                <th>Этажи</th>
            </tr>
            <?php foreach($models as $model):?>
                <tr onclick="selectBuilding(this)" data-url="<?=Yii::$app->urlManager->createUrl(['/crm/floor/table-floor','build'=>$model->id])?>">
                    <td><?=$model->fullAddress?></td>
                    <td><?=$model->countFloors?></td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
    <div class="col-lg-6 col-xs-12">
        <h2>Список этажей</h2>
        <div id="level-building"></div>
    </div>
</div>