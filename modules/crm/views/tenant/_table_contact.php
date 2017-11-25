<?php
    use yii\helpers\Html;
?>

<table class="table table-bordered" data-url="<?=\yii\helpers\Url::toRoute(['/crm/tenant/contact-table','id' => $model->id])?>">
    <tr>
        <th>Тип контакта</th>
        <th>Значение</th>
        <th>Описание</th>
        <?php if (Yii::$app->user->can(\app\models\Contact::ROLE_CONTACT_DELETE)):?>
            <th>Удаление</th>
        <?php endif;?>
    </tr>
    <?php foreach($model->contacts as $contact):?>
        <tr>
            <td><?=$contact->typeContact->name;?></td>
            <td><?php
                if (Yii::$app->user->can(\app\models\Contact::ROLE_CONTACT_UPDATE))
                    echo Html::a(
                        $contact->value,
                        ['/crm/contact/update','id' => $contact->id],
                        [
                            'onclick' => 'showForm(this, event)',
                        ]
                    );
                else
                    echo $contact->value;
                ?>
            </td>
            <td><?=$contact->description;?></td>
            <?php if (Yii::$app->user->can(\app\models\Contact::ROLE_CONTACT_DELETE)):?>
                <td><?=Html::a(
                        '<span class="glyphicon glyphicon-remove"></span>',
                        ['/crm/contact/delete','id'=>$contact->id],
                        [
                            'onclick' => 'rmContact(this,event)',
                        ]
                    )?>
                </td>
            <?php endif;?>
        </tr>
    <?php endforeach;?>
</table>