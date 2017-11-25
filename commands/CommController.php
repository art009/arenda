<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Agreement;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CommController extends Controller
{
    private $mailSend = 'akadem@predel57.ru';

    public function actionNotification()
    {
        $models = Agreement::find()
            ->where([
                '>' , 'date_to' , strtotime('now')
            ])
            ->andWhere([
                '<' , 'date_to' , strtotime('+' .Agreement::WARN_DAYS. ' day')
            ])->all();
        if ($models){
            return \Yii::$app->mailer->compose(['html' => $view . '-html', 'text' => $view . '-text'], ['models' => $models])
                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                ->setTo($this->mailSend)
                ->setSubject('Уведомление об окончание договора ' . \Yii::$app->name)
                ->send();
        }
    }
}
