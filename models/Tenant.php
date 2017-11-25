<?php

namespace app\models;

use budyaga\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "tenant".
 *
 * @property integer $id
 * @property string $name
 * @property integer type_tenant
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Tenant extends \yii\db\ActiveRecord
{
    const ROLE_TENANT_INDEX             = 'roleTenantIndex';
    const ROLE_TENANT_CREATE            = 'roleTenantCreate';
    const ROLE_TENANT_UPDATE            = 'roleTenantUpdate';
    const ROLE_TENANT_DELETE            = 'roleTenantDelete';
    const ROLE_TENANT_CONTACT_TABLE     = 'roleTenantContactTable';

    const TYPE_TENANT_ACTUAL = 10; //фактический адрес
    const TYPE_TENANT_LEGAL = 20; //юридический адрес

    public static function labelTypeTenant()
    {
        return [
            self::TYPE_TENANT_ACTUAL => 'Фактический',
            self::TYPE_TENANT_LEGAL => 'Юридический',
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tenant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'type_tenant'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['type_tenant', 'default' ,  'value' => self::TYPE_TENANT_ACTUAL],
            ['type_tenant', 'in' ,  'range' => array_keys(self::labelTypeTenant())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'name' => 'Организация',
            'type_tenant' => 'Тип адреса',
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
    // добавил запись
    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }
    // список контактов
    public function getContacts()
    {
        return $this->hasMany(Contact::className(),['tenant' => 'id']);
    }
    // для вывода с типом арендатора
    public function getNameWithType()
    {
        return $this->name . '(' . self::labelTypeTenant()[$this->type_tenant] . ')';
    }
}
