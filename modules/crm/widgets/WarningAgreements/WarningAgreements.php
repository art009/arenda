<?php
namespace app\modules\crm\widgets\WarningAgreements;

use app\models\Agreement;
use yii\base\Widget;

class WarningAgreements extends Widget
{
    public function run()
    {
        $models = Agreement::find()
            ->where([
                '>' , 'date_to' , strtotime('now')
            ])
            ->andWhere([
                '<' , 'date_to' , strtotime('+' .Agreement::WARN_DAYS. ' day')
            ])->all();
        if($models)
            return $this->render('warningAgreements',['models' => $models]);
    }
}