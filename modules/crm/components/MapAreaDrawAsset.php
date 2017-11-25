<?php
namespace app\modules\crm\components;

use yii\web\AssetBundle;

class MapAreaDrawAsset extends AssetBundle
{
    public $basePath = '@webroot/script/MapAreaDraw';
    public $baseUrl = '@web/script/MapAreaDraw';
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/common.js',
        'js/scripts.js',
    ];
}