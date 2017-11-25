<?php

namespace app\modules\crm\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Default controller for the `crm` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
//                        'actions' => ['create'],
//                        'roles' => [Agreement::ROLE_AGREEMENT_INDEX],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
