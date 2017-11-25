<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Floor */
/* @var $image app\models\forms\UploadFile */

$this->title = 'Изменение этажа: ' . $model->label;
$this->params['breadcrumbs'][] = ['label' => 'Этажи', 'url' => ['index','id' =>  $model->building]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="floor-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image,
    ]) ?>

</div>
