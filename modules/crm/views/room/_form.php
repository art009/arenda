<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Room */
/* @var $form yii\widgets\ActiveForm */
?>

<?php app\modules\crm\components\AlertBootsrap::run(); ?>

<?php if (Yii::$app->session->hasFlash('success')): ?>

    <script type="text/javascript">
        setTimeout(function(){
//            $('#modal-modif').modal('hide');
            location.reload();
        },1500)
    </script>

<?php else: ?>

    <div class="room-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'number_room')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'number_bti')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'area_room')->textInput() ?>

        <?php // $form->field($model, 'floor')->textInput() ?>

        <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::a(
                $model->isNewRecord ? 'Добавить' : 'Изменить',
                $url,
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'onclick' => 'saveForm(this,event)'
                ]
            ) ?>

            <?= Html::a(
                'Удалить координаты',
                $urlCoords,
                [
                    'class' => 'btn btn-danger',
                    'onclick' => 'removeCoords(this,event)',
                ]
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php endif;?>