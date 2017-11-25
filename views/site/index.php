<?php
use app\widgets\AuthorizationWidget\AuthorizationWidget;
/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="row">
        <div class="col-lg-offset-4 col-lg-4 col-xs-12">
            <?= AuthorizationWidget::widget() ?>
        </div>
    </div>
</div>
