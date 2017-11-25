<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Training */

$this->title = 'Изменить видео обучение: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Обучение', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="training-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
