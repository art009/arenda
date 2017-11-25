<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TypeContact */

$this->title = 'Изменить тип контакта: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы контактов', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="type-contact-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
