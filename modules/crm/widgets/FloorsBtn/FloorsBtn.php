<?php
namespace app\modules\crm\widgets\FloorsBtn;

use yii\base\Widget;

class FloorsBtn extends Widget
{
    public $build;

    public function init()
    {
        return parent::init();
    }

    public function run()
    {
        if ($this->build == NULL)
            return '';

        $model = \app\models\Building::findOne($this->build);
        if ($model)
            return $this->render('floorsBtn',['model' => $model]);
    }
}