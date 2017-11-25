<?php

namespace app\modules\crm\controllers;

use app\models\Agreement2quarter;
use app\models\Room;
use Yii;
use app\models\Agreement;
use app\models\Floor;
use app\models\search\Agreement as AgreementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * AgreementController implements the CRUD actions for Agreement model.
 */
class AgreementController extends Controller
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
                        'roles' => [Agreement::ROLE_AGREEMENT_INDEX],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [Agreement::ROLE_AGREEMENT_CREATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [Agreement::ROLE_AGREEMENT_UPDATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [Agreement::ROLE_AGREEMENT_DELETE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['map'],
                        'roles' => [Agreement::ROLE_AGREEMENT_MAP],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['info'],
                        'roles' => [Agreement::ROLE_AGREEMENT_INFO],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add-quarters'],
                        'roles' => [Agreement::ROLE_AGREEMENT_ADD_QUARTERS],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete-quarter'],
                        'roles' => [Agreement::ROLE_AGREEMENT_DELETE_QUARTERS],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Agreement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($quarters)
    {
        $model = new Agreement();
        $quarter = $this->findRoomModel($quarters);
        $model->quarter = $quarters;
        $model->date_from = date('d.m.Y');
        $model->date_to = date('d.m.Y',strtotime("+1 month"));
        $model->agreement_date = date('d.m.Y');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Новый договор #'.($model->inner_number)?$model->inner_number:$model->id. ' от ' . date('d.m.Y',$model->agreement_date) . ' на пемещение №' . $quarter->number_room . ' добавлен.');
            return $this->redirect(['/crm/agreement/map','id'=>$quarter->floor]);
        }
        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('create', [
            'model' => $model,
            'quarter' => $quarter,
        ]);
    }

    /**
     * Updates an existing Agreement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->tenant_name = $model->ten->name;
        $model->date_from = date('d.m.Y',$model->date_from);
        $model->date_to = date('d.m.Y',$model->date_to);
        $model->agreement_date = ($model->agreement_date)?date('d.m.Y',$model->agreement_date):date('d.m.Y');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Договор изменен.');
            return $this->redirect(['index']);
        }
        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('update', [
            'model' => $model,
            'quarter' => null,
        ]);
    }

    /**
     * Deletes an existing Agreement model.
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
     * Finds the Agreement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agreement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agreement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая информация не найдена.');
        }
    }

    protected function findFloorModel($id)
    {
        if (($model = Floor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая информация не найдена.');
        }
    }

    protected function findRoomModel($id)
    {
        if (($model = Room::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая информация не найдена.');
        }
    }

    // карта этажа
    public function actionMap($id)
    {
        $model = $this->findFloorModel($id);
        return $this->render('map',['model' => $model]);
    }
    // информация объекта
    public function actionInfo($quarters)
    {
        if (!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая информация не найдена.');

        $model = $this->findRoomModel($quarters);
        return $this->renderAjax('_info',['model' => $model]);
    }
    // добавим помещение к договору
    public function actionAddQuarters($quarter)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findRoomModel($quarter);
            $agreement = $this->findModel(Yii::$app->request->post('agreement'));
            $agreement->link('quarters', $model);
        }
    }
    // удалим связь договор - помещение
    public function actionDeleteQuarter($agreement,$quarter)
    {
        Agreement2quarter::findOne(['agreement_id' => $agreement, 'quarter_id' => $quarter])->delete();
    }
}
