<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

$this->title = 'Изменение договора: №' . $model->id . ' от ' . $model->date_from;
$this->params['breadcrumbs'][] = ['label' => 'Список договоров', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="agreement-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'quarter' => $quarter,
    ]) ?>

</div>
