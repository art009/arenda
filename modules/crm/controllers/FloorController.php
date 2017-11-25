<?php

namespace app\modules\crm\controllers;

use app\models\Building;
use app\models\forms\UploadFile;
use Yii;
use app\models\Floor;
use app\models\search\Floor as FloorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * FloorController implements the CRUD actions for Floor model.
 */
class FloorController extends Controller
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
                        'roles' => [Floor::ROLE_FLOOR_INDEX],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [Floor::ROLE_FLOOR_CREATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [Floor::ROLE_FLOOR_UPDATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [Floor::ROLE_FLOOR_DELETE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['map'],
                        'roles' => [Floor::ROLE_FLOOR_MAP],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['table-floor'],
                        'roles' => [Floor::ROLE_FLOOR_TABLE_FLOOR],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Floor models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new FloorSearch();
        $searchModel->building = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $buildingModel = $this->findBuildingModel($id);

        return $this->render('index', [
            'buildingModel' => $buildingModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Floor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($building)
    {
        $model = new Floor();
        $image = new UploadFile(['scenario' => UploadFile::SCENARIO_CREATE]);

        $model->building = $building;

        if (Yii::$app->request->isPost) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
            $image->path = Floor::MAP_PATH;
            if ($image->upload())
                $model->map = $image->new_name;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Этаж добавлен.');
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('create', [
            'model' => $model,
            'image' => $image,
        ]);
    }

    /**
     * Updates an existing Floor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = new UploadFile();

        if (Yii::$app->request->isPost) {
            $image->imageFile = UploadedFile::getInstance($image, 'imageFile');
            $image->path = Floor::MAP_PATH;
            if ($image->upload())
                $model->map = $image->new_name;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success','Этаж изменен.');
                return $this->redirect(['index', 'id' => $model->building]);
            }
        }

        if ($model->hasErrors()){
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->render('update', [
            'model' => $model,
            'image' => $image,
        ]);
    }

    /**
     * Deletes an existing Floor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $building = $model->building;
        $model->delete();

        return $this->redirect(['index','id' => $building]);
    }

    /**
     * Finds the Floor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Floor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Floor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }

    protected function findBuildingModel($id)
    {
        if (($model = Building::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }

    public function actionMap($id)
    {
        $model = $this->findModel($id);
        return $this->render('map',['model' => $model]);
    }

    public function actionTableFloor($build)
    {
        $model = $this->findBuildingModel($build);
        return $this->renderAjax('_tableFloor',['model' => $model]);
    }
}
