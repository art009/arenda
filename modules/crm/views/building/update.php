<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Building */

$this->title = 'Изменение строения: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Строения', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="building-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
