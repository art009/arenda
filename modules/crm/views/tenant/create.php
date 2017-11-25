<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tenant */

$this->title = 'Новый арендатор';
$this->params['breadcrumbs'][] = ['label' => 'Список арендаторов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tenant-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
