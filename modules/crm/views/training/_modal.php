<div id="youtube_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <iframe width="560" height="315" frameborder="0" allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
</div>


<?php
/* @var $this yii\web\View */
echo $this->registerJs('
    $("#youtube_modal").on("hidden.bs.modal", function () {
        $(this).find("iframe").attr("src","")
    })
',$this::POS_READY);
?>