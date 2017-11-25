<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
?>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        [
            'label' => 'Арендаторы',
            'url' => ['/crm/tenant'],
            'visible' => Yii::$app->user->can(\app\models\Tenant::ROLE_TENANT_INDEX),
        ],
        [
            'label' => 'Договоры',
            'url' => ['/crm/agreement'],
            'visible' => Yii::$app->user->can(\app\models\Agreement::ROLE_AGREEMENT_INDEX),
        ],
        [
            'label' => 'Здания',
            'url' => ['/crm/building'],
            'visible' => Yii::$app->user->can(\app\models\Building::ROLE_BUILDING_INDEX),
        ],
//        ['label' => 'Заявка', 'url' => ['/site/contact']],
        [
            'label' => 'Справочники',
            'url' => ['#'],
            'items' => [
                [
                    'label' => 'Типы контактов',
                    'url' => ['/crm/type-contact'],
                    'visible' => Yii::$app->user->can(\app\models\TypeContact::ROLE_TYPE_CONTACT_INDEX),
                ],
            ],
            'visible' => Yii::$app->user->can(\app\models\TypeContact::ROLE_TYPE_CONTACT_INDEX),
        ],
        [
            'label' => 'Доступ',
            'url' => ['#'],
            'items' => [
                ['label' => 'Пользователи', 'url' => ['/user/admin']],
                ['label' => 'Права доступа', 'url' => ['/user/rbac']],
            ],
        ],
        [
            'label' => 'Обучение',
            'url' => ['/crm/training'],
            'visible' => Yii::$app->user->can(\app\models\Training::ROLE_TRAINING_INDEX) ||
                Yii::$app->user->can(\app\models\Training::ROLE_TRAINING_CONTROLL),
        ],
        Yii::$app->user->isGuest ? (
        ['label' => 'Вход', 'url' => ['/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/logout'], 'post', ['class' => 'navbar-form'])
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>'
        )
    ],
]);
NavBar::end();
?>