<?php
namespace app\modules\crm\components;

use yii\web\AssetBundle;

class HighlightingMapAsset extends AssetBundle
{
    public $basePath = '@webroot/script';
    public $baseUrl = '@web/script';
    public $css = [
    ];
    public $js = [
        'highlightingMap/jquery.maphilight.js',
        'jquery.imagemapster.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}