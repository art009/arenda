<?php

namespace app\models;

use budyaga\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "agreement".
 *
 * @property integer $id
 * @property integer $tenant
 * @property integer $date_from
 * @property integer $date_to
 * @property integer $quarters
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Agreement extends \yii\db\ActiveRecord
{
    const ROLE_AGREEMENT_INDEX = 'roleAgreementIndex';
    const ROLE_AGREEMENT_CREATE = 'roleAgreementCreate';
    const ROLE_AGREEMENT_UPDATE = 'roleAgreementUpdate';
    const ROLE_AGREEMENT_DELETE = 'roleAgreementDelete';
    const ROLE_AGREEMENT_MAP = 'roleAgreementMap';
    const ROLE_AGREEMENT_INFO = 'roleAgreementInfo';
    const ROLE_AGREEMENT_ADD_QUARTERS = 'roleAgreementAddQuarters';
    const ROLE_AGREEMENT_DELETE_QUARTERS = 'roleAgreementDeleteQuarters';

    const WARN_DAYS = 30; // за сколько дней предупреждать об окончание договора
    public $tenant_name;
    public $quarter;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tenant_name', 'date_from', 'date_to'], 'required'],
            [['tenant', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            ['date_from','validateDataFrom'],
            ['date_to','validateDataFrom'],
            ['quarter','integer'],
            ['inner_number', 'string', 'max'=>50],
            ['agreement_date', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'agreement_date'],
            ['date_from', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'date_from'],
            ['date_to', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'date_to'],
            ['date_from', 'compare', 'compareAttribute' => 'date_to', 'operator' => '<', 'enableClientValidation' => false],
        ];
    }

    /**
     * @param Model $model the model being validated
     * @param string $attribute the attribute being validated
     * @return boolean whether the rule should be applied
     */
    public function validateDataFrom($attribute, $params)
    {
        if ($this->ten->type_tenant != Tenant::TYPE_TENANT_ACTUAL)
            return true;

        $val = strtotime($this->$attribute);
        $records = self::find()
            ->select('agreement.id')
            ->joinWith('ten')
            ->joinWith('a2q')
            ->where('agreement2quarter.quarter_id = :quarter_id',[':quarter_id' => $this->quarter])
            ->andWhere(['<', 'date_from', $val])
            ->andWhere(['>', 'date_to', $val])
            ->andWhere(['tenant.type_tenant' => Tenant::TYPE_TENANT_ACTUAL]) // актуальность для фактического адреса
            ->andFilterWhere(['not in', 'agreement.id', $this->id])
            ->count('agreement.id');

        if ($records > 0){
            $this->addError($attribute, $this->attributeLabels()[$attribute].' для данного помещения занято.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inner_number' => 'Внутренний номер',
            'agreement_date' => 'Дата заключения договора',
            'tenant' => 'Арендатор',
            'tenant_name' => 'Арендатор',
            'date_from' => 'Дата начала договора',
            'date_to' => 'Дата окончания договора',
            'created_at' => 'Добавлен',
            'updated_at' => 'Изменен',
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
    // добавил
    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }
    // договор помещение
    public function getA2q()
    {
        return $this->hasMany(Agreement2quarter::className(),['agreement_id' => 'id']);
    }
    // помещения
    public function getQuarters()
    {
        return $this->hasMany(Room::className(),['id' => 'quarter_id'])->via('a2q');
    }
    // полная площадь по помещеним
    public function getAreaAgreement()
    {
        return self::find()
            ->select(['room.area_room'])
            ->joinWith('quarters')
            ->where([
                'agreement.id' => $this->id
            ])
            ->sum('room.area_room');
    }
    // этаж
    public function getFloor()
    {
        return $this->hasMany(Floor::className(),['id' => 'floor_id'])->via('a2q');
    }
    // здание
    public function getBuild()
    {
        return $this->hasMany(Floor::className(),['id' => 'building_id'])->via('a2q');
    }
    // арендатор
    public function getTen()
    {
        return $this->hasOne(Tenant::className(),['id' => 'tenant']);
    }
    // договор активный
    public function isActive()
    {
        if (($this->date_from < strtotime('now')) AND ($this->date_to > strtotime('now')))
            return true;
        else
            return false;
    }
    // предупреждение по договору
    public function isWarning()
    {
        $warn_day = $this->date_to - 60*60*24*Agreement::WARN_DAYS;
        if (($warn_day < strtotime('now')) AND ($this->date_to > strtotime('now')))
            return true;
        else
            return false;
    }

    public function afterSave($insert,$changedAttributes)
    {
        if($this->quarter){
            $quarter = Room::findOne($this->quarter);
            $this->link('quarters',$quarter);
        }

        return parent::afterSave($insert,$changedAttributes);
    }

    public function beforeDelete()
    {
        Agreement2quarter::deleteAll(['agreement_id' => $this->id]) ;

        return parent::beforeDelete();
    }
}
