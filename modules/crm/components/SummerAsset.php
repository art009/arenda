<?php
namespace app\modules\crm\components;

use yii\web\AssetBundle;

class SummerAsset extends AssetBundle
{
    public $basePath = '@webroot/script/summer';
    public $baseUrl = '@web/script/summer';
    public $css = [
        'main.css',
    ];
    public $js = [
        'summerHTMLImageMapCreator.js',
    ];
}