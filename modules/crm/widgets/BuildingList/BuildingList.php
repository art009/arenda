<?php
namespace app\modules\crm\widgets\BuildingList;

use app\models\Building;
use yii\base\Widget;
use app\models\Floor;
use Yii;

class BuildingList extends Widget
{
    public function init()
    {
        return parent::init();
    }

    public function run()
    {
        $models = Building::find()->all();
        if ($models && Yii::$app->user->can(Floor::ROLE_FLOOR_TABLE_FLOOR))
            return $this->render('buildingList',['models' => $models]);
    }
}