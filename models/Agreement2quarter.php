<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agreement2quarter".
 *
 * @property integer $agreement_id
 * @property integer $quarter_id
 * @property integer $floor_id
 * @property integer $building_id
 */
class Agreement2quarter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement2quarter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agreement_id', 'quarter_id'], 'required'],
            [['agreement_id', 'quarter_id', 'floor_id', 'building_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'agreement_id' => 'Договор',
            'quarter_id' => 'Помещение',
            'floor_id' => 'Этаж',
            'building_id' => 'Здание',
        ];
    }
    // список договоров
    public function getAgreement()
    {
        return $this->hasOne(Agreement::className(),['id' => 'agreement_id']);
    }
    // список помещений
    public function getQuarter()
    {
        return $this->hasOne(Room::className(),['id' => 'quarter_id']);
    }
    // номер этажа
    public function getFloor()
    {
        return $this->hasOne(Floor::className(),['id' => 'floor_id']);
    }
    // эдание
    public function getBuilding()
    {
        return $this->hasOne(Building::className(),['id' => 'building_id']);
    }

    public function beforeSave($insert)
    {
        $this->floor_id = $this->quarter->floor;
        $this->building_id = $this->quarter->building->id;
        return parent::beforeSave($insert);
    }
}
