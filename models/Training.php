<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "training".
 *
 * @property integer $id
 * @property string $video
 * @property string $title
 * @property string $description
 */
class Training extends \yii\db\ActiveRecord
{
    const ROLE_TRAINING_INDEX       = 'roleTrainingIndex';
    const ROLE_TRAINING_CONTROLL    = 'roleTrainingControll';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video', 'title'], 'required'],
            [['description'], 'string'],
            [['video', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'video' => 'Ссылка на YouTube',
            'title' => 'Название',
            'description' => 'Описание',
        ];
    }
}
