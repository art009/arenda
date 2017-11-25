<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

$this->title = 'Новый договор';
$this->params['breadcrumbs'][] = ['label' => 'Список договоров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'quarter' => $quarter,
    ]) ?>

</div>
