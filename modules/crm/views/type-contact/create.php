<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TypeContact */

$this->title = 'Новый тип контакта';
$this->params['breadcrumbs'][] = ['label' => 'Типы контактов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-contact-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php app\modules\crm\components\AlertBootsrap::run(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
