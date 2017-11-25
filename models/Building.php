<?php

namespace app\models;

use budyaga\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
//use app\modules\crm\components\UserBehaviors;

/**
 * This is the model class for table "building".
 *
 * @property integer $id
 * @property string $city
 * @property string $street
 * @property integer $house
 * @property integer $building
 * @property integer $letter
 * @property string $coordinates
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Building extends \yii\db\ActiveRecord
{
    const ROLE_BUILDING_INDEX = 'roleBuildingIndex';
    const ROLE_BUILDING_CREATE = 'roleBuildingCreate';
    const ROLE_BUILDING_UPDATE = 'roleBuildingUpdate';
    const ROLE_BUILDING_DELETE = 'roleBuildingDelete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'building';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city', 'street', 'house'], 'required'],
            [['house', 'building', 'letter', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['city', 'street', 'coordinates'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'Город',
            'street' => 'Улица',
            'house' => 'Номер дома',
            'building' => 'Корпус',
            'letter' => 'Литера',
            'coordinates' => 'Координаты',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
            'created_by' => 'Создал',
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
    // автор записи
    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }

    public function getFullAddress()
    {
        $building = ($this->building)?'/'.$this->building:'';
        $letter = ($this->letter)?'-'.$this->letter:'';
        return $this->city.', '.$this->street.', '.$this->house.$building.$letter;
    }

    // список этажей
    public function getFloors()
    {
        return $this->hasMany(Floor::className(),['building' => 'id']);
    }

    // кол-во этажей
    public function getCountFloors()
    {
        return $this->hasMany(Floor::className(),['building' => 'id'])->count('id');
    }
    // список сооружений
    public static function getArrayBuilding()
    {
        $res_array = [];
        $models = self::find()->all();
        foreach($models as $model)
            $res_array[$model->id] = $model->fullAddress;

        return $res_array;
    }
}
