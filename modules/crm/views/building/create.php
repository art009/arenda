<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Building */

$this->title = 'Новое строение';
$this->params['breadcrumbs'][] = ['label' => 'Строения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
