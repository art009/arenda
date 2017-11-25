<?php
    use app\modules\crm\widgets\BuildingList\BuildingList;
    use yii\helpers\Html;
    $this->title = 'Панель управления';
?>

<div class="crm-default-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <?=app\modules\crm\widgets\WarningAgreements\WarningAgreements::widget()?>
    </div>
    <?=BuildingList::widget(); ?>
</div>
