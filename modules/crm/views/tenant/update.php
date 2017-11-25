<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tenant */

$this->title = 'Изменение данных арендатора: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Список арендаторов', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="tenant-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
