<?php

namespace app\models;

use budyaga\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "type_contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $parametr
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class TypeContact extends \yii\db\ActiveRecord
{
    const ROLE_TYPE_CONTACT_INDEX      = 'roleTypeContactIndex';
    const ROLE_TYPE_CONTACT_CREATE     = 'roleTypeContactCreate';
    const ROLE_TYPE_CONTACT_UPDATE     = 'roleTypeContactUpdate';
    const ROLE_TYPE_CONTACT_DELETE     = 'roleTypeContactDelete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'parametr'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'name' => 'Тип',
            'parametr' => 'Параметры',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
            'created_by' => 'Добавил',
            'updated_by' => 'Изменил',
        ];
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }

    public static function listTypeContact()
    {
        return ArrayHelper::map(self::find()->all(),'id','name');
    }
}
