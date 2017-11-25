<?php
use yii\helpers\Html;
?>

<div class="well">
    <?php foreach($model->floors as $floor):?>
        <?=Html::a(
            $floor->label,
            ['map','id' => $floor->id],
            [
                'class' => 'btn btn-default'
            ]
            );
        ?>
    <?php endforeach;?>
</div>