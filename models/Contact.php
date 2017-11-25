<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use budyaga\users\models\User;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property integer $type_contact
 * @property string $value
 * @property integer $tenant
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Contact extends \yii\db\ActiveRecord
{
    const ROLE_CONTACT_CREATE = 'roleContactCreate';
    const ROLE_CONTACT_UPDATE = 'roleContactUpdate';
    const ROLE_CONTACT_DELETE = 'roleContactDelete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_contact', 'value', 'tenant'], 'required'],
            [['type_contact', 'tenant', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['value'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'type_contact' => 'Тип контакта',
            'value' => 'Значение',
            'tenant' => 'Арендатор',
            'description' => 'Описание',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменение',
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

    public function getTypeContact()
    {
        return $this->hasOne(TypeContact::className(),['id' => 'type_contact']);
    }

    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }
}
