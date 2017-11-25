<?php

namespace app\modules\crm\controllers;

use Yii;
use app\models\Contact;
use app\models\search\Contact as ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [Contact::ROLE_CONTACT_CREATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [Contact::ROLE_CONTACT_UPDATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [Contact::ROLE_CONTACT_DELETE],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tenant)
    {
        $model = new Contact();
        $model->tenant = $tenant;

        if (!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Контакт добавлен.');
        }
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }
        return $this->renderAjax('_form', [
            'model' => $model,
            'url' => Yii::$app->urlManager->createUrl(['/crm/contact/create','tenant' => $model->tenant]),
        ]);
    }

    /**
     * Updates an existing Contact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Данные изменены.');
        }
        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'url' => Yii::$app->urlManager->createUrl(['/crm/contact/update','id' => $model->id]),
        ]);
    }

    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax){
            $this->findModel($id)->delete();
            Yii::$app->end();
        }

        throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
//        return $this->redirect(['index']);
    }

    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
}
