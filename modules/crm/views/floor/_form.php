<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Floor */
/* @var $form yii\widgets\ActiveForm */
/* @var $image app\models\forms\UploadFile */
?>

<div class="floor-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'building')->dropDownList(\app\models\Building::getArrayBuilding()) ?>

    <?= $form->field($image, 'imageFile')->fileInput()->label('План этажа') ?>

    <?php if($model->map):?>
        <?=Html::img($model->showMap,['class' => 'img-responsive'])?>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
