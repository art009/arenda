<?php
namespace app\modules\crm\components;
/*
primary
success
info
warning
danger
*/
use Yii;

class AlertBootsrap {
    public static function run()
    {
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            $bnt_close = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            echo '<div class="alert alert-' . $key . ' alert-dismissable">' . $bnt_close . $message . '</div>';
        }
    }
}
?>