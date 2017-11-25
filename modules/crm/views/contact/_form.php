<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TypeContact;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<?php app\modules\crm\components\AlertBootsrap::run(); ?>

<?php if (Yii::$app->session->hasFlash('success')): ?>

    <script type="text/javascript">
        setTimeout(function(){
            $('#modal-modif').modal('hide');
        },3000)
    </script>

<?php else: ?>

    <div class="contact-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'type_contact')->dropDownList(TypeContact::listTypeContact()) ?>

        <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::a(
                $model->isNewRecord ? 'Добавить' : 'Изменить',
                $url,
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'onclick' => 'saveForm(this,event)',
                ]
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php endif;?>
