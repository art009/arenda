<?php
namespace app\helpers;

use yii\helpers\FileHelper as MainFileHelper;

class FileHelper
{
    public static function getRandomFileName($path, $extension='')
    {
        $extension = $extension ? '.' . $extension : '';
        $path = $path . '/';
//        if (!is_dir($path))
//            MainFileHelper::createDirectory($path);

        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path . $name . $extension;
        } while (file_exists($file));

        return $name;
    }
}
?>