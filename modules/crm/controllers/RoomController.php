<?php

namespace app\modules\crm\controllers;

use app\models\Floor;
use app\models\RoomCoordinates;
use Yii;
use app\models\Room;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
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
                        'actions' => ['create'],
                        'roles' => [Room::ROLE_ROOM_CREATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [Room::ROLE_ROOM_UPDATE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [Room::ROLE_ROOM_DELETE],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['coordinates','delete-coords','point-coordinate'],
                        'roles' => [Floor::ROLE_FLOOR_MAP],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($coordinate,$floor)
    {
        $model = new Room();
        $model->coordinates = $coordinate;
        $model->floor = $floor;

        $url = Yii::$app->urlManager->createUrl(['/crm/room/create','coordinate'=>$coordinate,'floor'=>$floor]);
        $urlCoords = Yii::$app->urlManager->createUrl(['/crm/room/delete-coords','id'=>$coordinate]);

        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Данные внесены.');
        }

        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'url' => $url,
            'urlCoords' => $urlCoords,
        ]);
    }

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $url = Yii::$app->urlManager->createUrl(['/crm/room/update','id'=>$id]);
        $urlCoords = Yii::$app->urlManager->createUrl(['/crm/room/delete-coords','id'=>$model->coordinates]);

        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if ($model->load(Yii::$app->request->post()) && $model->save())
            Yii::$app->session->setFlash('success','Данные изменены.');

        if ($model->hasErrors()) {
            Yii::$app->session->setFlash('danger',Html::errorSummary($model));
        }

        return $this->renderAjax('_form', [
            'model' => $model,
            'url' => $url,
            'urlCoords' => $urlCoords,
        ]);
    }

    /**
     * Deletes an existing Room model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->request->isAjax)
            $this->findModel($id)->delete();
        Yii::$app->end();
//        return $this->redirect(['index']);
    }

    /**
     * Finds the Room model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if (($model = Room::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }

    public function actionCoordinates()
    {
        if (Yii::$app->request->post()){
            $floor = Yii::$app->request->post('floor');

            $txtFile = UploadedFile::getInstanceByName('file');

            if($txtFile){
                $fileName = Yii::getAlias('@webroot').'/uploads/tmp/coord.txt';
                $txtFile->saveAs($fileName);
                $dateForPars = file_get_contents($fileName);

                if (preg_match_all('|coords="(.*?)">|sei', $dateForPars, $arr)) {
                    $count = 0;
                    // удалим все записи для этажа
                    RoomCoordinates::deleteAll(['floor' => $floor]);
                    foreach ($arr[1] as $coord){
                        $coordModel = new RoomCoordinates;
                        $coordModel->coordinates = $coord;
                        $coordModel->floor = $floor;
                        $coordModel->save();
                        $count++;
                    }
                    Yii::$app->session->setFlash('success','Успешно добавлено '.$count.' точек.');
                }

                if (is_file($fileName))
                    unlink($fileName);

//                exit;
            }

            return $this->redirect(['/crm/floor/map','id' => $floor]);
        }
        return $this->redirect('/crm/');
    }

    // удаление координат
    function actionDeleteCoords($id)
    {
        if(Yii::$app->request->isAjax)
            $this->findModelCordinates($id)->delete();
        Yii::$app->end();
    }

    protected function findModelCordinates($id)
    {
        if(!Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');

        if (($model = RoomCoordinates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }
    }
    // сохраним точечные координаты
    public function actionPointCoordinate($floor)
    {
        if ($coordinates = Yii::$app->request->post('coordinates')){
            $cord_array = preg_split('/\n|\r\n?/', $coordinates);
            foreach($cord_array as $cord){
                if ($cord != ''){
                    $model = new RoomCoordinates();
                    $model->floor = $floor;
                    $model->coordinates = $cord;
                    $model->save();
                }
            }
        }
        Yii::$app->end();
    }
}
