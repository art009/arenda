<?php

namespace app\models;

use Yii;
use budyaga\users\models\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "floor".
 *
 * @property integer $id
 * @property string $label
 * @property integer $building
 * @property string $map
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Floor extends \yii\db\ActiveRecord
{
    const ROLE_FLOOR_INDEX = 'roleFloorIndex';
    const ROLE_FLOOR_CREATE = 'roleFloorCreate';
    const ROLE_FLOOR_UPDATE = 'roleFloorUpdate';
    const ROLE_FLOOR_DELETE = 'roleFloorDelete';
    const ROLE_FLOOR_MAP = 'roleFloorMap';
    const ROLE_FLOOR_TABLE_FLOOR = 'roleFloorTableFloor';

    const MAP_PATH = '/uploads/floors';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'building', 'map'], 'required'],
            [['building', 'created_at', 'updated_at', 'created_by', 'updated_by', 'number_velel'], 'integer'],
            [['label'], 'string', 'max' => 100],
            [['map'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Метка',
            'building' => 'Строение',
            'map' => 'План этажа',
            'number_velel' => 'Порядковый номер этажа',
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

    // добавил
    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }

    // строение
    public function getBuild()
    {
        return $this->hasOne(Building::className(),['id' => 'building']);
    }

    // удаляем файл
    public function deleteFile()
    {
        $file = Yii::getAlias('self::MAP_PATH'.$this->map);
        if (is_file($file)) unset($file);
    }

    public function beforeDelete()
    {
        $this->deleteFile();
        return parent::beforeDelete();
    }
    // ссылка на карту этажа
    public function getShowMap()
    {
        return self::MAP_PATH . '/' . $this->map;
    }

    // последний этаж в здание
    public function getMaxLevel()
    {
        $max_level = $this->find()->select('number_velel')->where(['building' => $this->building])->max('number_velel');
        return ($max_level)?$max_level:1;
    }

    public function beforeSave($insert)
    {
        if (!$this->number_velel)
            $this->number_velel = (int)$this->maxLevel + 1;
        return parent::beforeSave($insert);
    }
    // список координат
    public function getCoordinates()
    {
        return $this->hasMany(RoomCoordinates::className(),['floor' => 'id']);
    }
    // офисы на этаже
    public function getRooms()
    {
        return $this->hasMany(Room::className(),['floor' => 'id']);
    }
    // список договоров на этаже
    public function getAgreements()
    {
        return $this
            ->hasMany(Agreement::className(),['id' => 'agreement_id'])
            ->viaTable('{{%agreement2quarter}}',['floor_id' => 'id']);
    }

    // общая площадь
    public function totalArea()
    {
        return self::find()
            ->select(['room.area_room'])
            ->joinWith('rooms',false)
            ->where([
                'floor' => $this->id
            ])
            ->sum('room.area_room');
    }

    // арендованная площадь
    public function rentArea()
    {
        $summ = (new \yii\db\Query())
            ->select(['q.area_room'])
            ->from(Agreement2quarter::tableName().' a2q')
            ->leftJoin(Room::tableName().' q','a2q.quarter_id = q.id')
            ->leftJoin(Agreement::tableName().' a','a2q.agreement_id = a.id')
            ->leftJoin(Tenant::tableName().' t','a.tenant = t.id')
            ->where([
                'a2q.floor_id' => $this->id,
                't.type_tenant' => Tenant::TYPE_TENANT_ACTUAL,
            ])
            ->andWhere([
                '<' , 'a.date_from' , strtotime('now')
            ])
            ->andWhere([
                '>' , 'a.date_to' , strtotime('now')
            ])
            ->sum('q.area_room');
        return $summ;
    }
}
