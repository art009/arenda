<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Building */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-form">
    <div class="row">
        <div class="col-lg-6 col-sm-12">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'house')->textInput() ?>

            <?= $form->field($model, 'building')->textInput() ?>

            <?= $form->field($model, 'letter')->textInput() ?>

            <?= $form->field($model, 'coordinates')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-6 col-sm-12">

        </div>
    </div>
</div>
