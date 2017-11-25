<?php
    use yii\helpers\Html;
    use app\models\Contact;
    /* @var $this yii\web\View */

    $this->registerJsFile('/script/contact.js');
?>

<?php if(Yii::$app->user->can(Contact::ROLE_CONTACT_CREATE)):?>
    <div class="btn-group">
        <?=Html::a(
            'Новый контакт',
            ['/crm/contact/create','tenant' => $model->id],
            [
                'class' => 'btn btn-default',
                'type' => 'button',
                'onclick' => 'showForm(this, event)',
            ]
        )?>
    </div>
<?php endif;?>

<div  id="contact-table">
    <?= $this->render('_table_contact',['model' => $model]);?>
</div>
