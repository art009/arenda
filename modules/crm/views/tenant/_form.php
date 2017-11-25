<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tenant;

/* @var $this yii\web\View */
/* @var $model app\models\Tenant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tenant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_tenant')->dropDownList(\app\models\Tenant::labelTypeTenant()) ?>

    <?php if (!$model->isNewRecord || Yii::$app->user->can(Tenant::ROLE_TENANT_CONTACT_TABLE)):?>
        <?= $this->render('_contact',['model' => $model])?>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?= $this->render('_modal')?>