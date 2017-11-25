<?php

namespace app\modules\crm\controllers;

use app\models\Contact;
use Yii;
use app\models\Tenant;
use app\models\search\Tenant as TenantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * TenantController implements the CRUD actions for Tenant model.
 */
class TenantController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [Tenant::ROLE_TENANT_INDEX],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [Tenant::ROLE_TENANT_CREATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [Tenant::ROLE_TENANT_UPDATE],
                    ],                    [
                        'allow' => true,
                        'actions' => ['Delete'],
                        'roles' => [Tenant::ROLE_TENANT_DELETE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['contact-table'],
                        'roles' => [Tenant::ROLE_TENANT_CONTACT_TABLE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['list'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tenant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TenantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Tenant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tenant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Новый арендатор внесен.');
            return $this->redirect(['update','id' => $model->id]);
        }
        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tenant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Информация об аредаторе изменена.');
            return $this->redirect(['index']);
        }
        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tenant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tenant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tenant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tenant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая информация не найдена.');
        }
    }

    public function actionContactTable($id)
    {
        if (Yii::$app->request->isAjax)
            return $this->renderAjax('_table_contact',['model' => $this->findModel($id)]);
        Yii::$app->end();
    }

    public function actionList($q = null) {
        $tenants = Tenant::find()
            ->select(['id','name'])
            ->where('name LIKE "%' . $q .'%"')
            ->orderBy('name')
            ->all();
        $out = [];
        foreach ($tenants as $tenant) {
            $contacts = Contact::findOne([
                'tenant' => $tenant->id,
                'type_contact' => 1,
            ]);

            $contact = '';

            if ($contacts)
                $contact = $contacts->value .' - ' . $contacts->description;

            $out[] = [
                'value' => $tenant->name,
                'contact' => $contact,
                'tenant' => $tenant->id,
            ];
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $out;
    }
}
