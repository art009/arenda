<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<?php app\modules\crm\components\AlertBootsrap::run(); ?>
<?php if (Yii::$app->user->can(\app\models\Agreement::ROLE_AGREEMENT_INDEX)):?>
    <div class="inform-data">
        <p class="h3">Список договоров</p>
        <div class="well well-sm">

            <?php if (Yii::$app->user->can(\app\models\Agreement::ROLE_AGREEMENT_CREATE)):?>
                <?=Html::a('<span class="glyphicon glyphicon-plus"></span> Новый договор',['/crm/agreement/create','quarters' => $model->id],['class'=>'btn btn-success'])?>
            <?php endif;?>

            <?=Html::a('<span class="glyphicon glyphicon-list"></span> Список договоров',['/crm/agreement/index','id' => $model->flr->building],['class'=>'btn btn-info'])?>

            <?php if (Yii::$app->user->can(\app\models\Agreement::ROLE_AGREEMENT_ADD_QUARTERS)):?>
                <?=Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span> Добавить договор',
                    '#',
                    [
                        'class' => 'btn btn-info',
                        'onclick' => 'addAgreement(this,event)',
                    ]
                )?>

            <div id="form-add-agreement" style="display: none" class="input-group">
                <?=Html::textInput('agreement','',[
                    'class' => 'form-control',
                    'placeholder' => 'Введите номер договора...'
                ])?>
                <span class="input-group-btn">
                    <button
                        class = "btn btn-default"
                        data-href = "<?=Yii::$app->urlManager->createUrl(['/crm/agreement/add-quarters','quarter' => $model->id])?>"
                        type = "button"
                        onclick = "saveAddAgreement(this)"
                    >
                        Добавить
                    </button>
                </span>
            </div>
            <?php endif;?>
        </div>

        <table class="table table-border table-responsive">
            <tr>
                <th width="20">#</th>
                <th>Организация</th>
                <th>Дата договора</th>
                <th>Площадь помещения</th>
            </tr>
            <?php foreach($model->agreements as $agreement): ?>
                <?php
                    if (!$agreement->isActive()) continue;
                    $class = '';
                    if ($agreement->isActive())
                        $class='class="success"';
                    if ($agreement->isWarning())
                        $class='class="warning"';
                ?>
                <tr <?=$class?>>
                    <th>#<?=$agreement->id?></th>
                    <th><?=Html::a(
                            $agreement->ten->nameWithType,
                            ['/crm/agreement/update','id' => $agreement->id]
                        );?>
                    </th>
                    <th><?=date('d.m.Y',$agreement->date_from) . '-' . date('d.m.Y',$agreement->date_to)?></th>
                    <th><?=$agreement->areaAgreement?> м <sup><small>2</small></sup></th>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
<?php endif;?>

<?=$this->render('_tableInfo',['model' => $model]);?>

