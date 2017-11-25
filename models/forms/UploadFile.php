<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\helpers\FileHelper;

class UploadFile extends Model
{
    const SCENARIO_CREATE = 'create';
    /**
    * @var UploadedFile
    */
    public $imageFile;
    public $path = '/uploads';
    public $new_name;

    public function rules()
    {
        return [
//            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'on' => self::SCENARIO_CREATE],
            ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['imageFile'];
        return $scenarios;
    }

    public function upload()
    {
        if ($this->validate() && $this->imageFile) {
            $filename = FileHelper::getRandomFileName($this->path,$this->imageFile->extension) . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(Yii::getAlias('@webroot') . $this->path . '/' . $filename);
            $this->new_name = $filename;
            return true;
        } else {
            return false;
        }
    }
}