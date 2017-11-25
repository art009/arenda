<?php

namespace app\models;

use budyaga\users\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "room".
 *
 * @property integer $id
 * @property string $number_room
 * @property integer $area_room
 * @property integer $floor
 * @property integer $coordinates
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Room extends \yii\db\ActiveRecord
{
    const ROLE_ROOM_CREATE = 'roleRoomCreate';
    const ROLE_ROOM_UPDATE = 'roleRoomUpdate';
    const ROLE_ROOM_DELETE = 'roleRoomDelete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number_room', 'floor', 'coordinates'], 'required'],
            [['area_room'], 'number'],
            [['floor', 'coordinates', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['number_room', 'number_bti'], 'string', 'max' => 20],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number_room' => 'Номер офиса',
            'number_bti' => 'Номер БТИ',
            'area_room' => 'Площадь помещения',
            'floor' => 'Этаж',
            'coordinates' => 'Координаты',
            'comment' => 'Комментарий',
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
    // добавил офис
    public function getCreater()
    {
        return $this->hasOne(User::className(),['id' => 'created_by']);
    }
    // послед изменил данные
    public function getUpdater()
    {
        return $this->hasOne(User::className(),['id' => 'updated_by']);
    }
    // договоры
    public function getAgreements()
    {
        return $this->hasMany(Agreement::className(),['id' => 'agreement_id'])->viaTable('{{%agreement2quarter}}',['quarter_id' => 'id']);
    }
    // этаж
    public function getFlr()
    {
        return $this->hasOne(Floor::className(),['id' => 'floor']);
    }
    // здание
    public function getBuilding()
    {
        return $this->hasOne(Building::className(),['id' => 'building'])->via('flr');
    }
    // активный договор
    public function getActiveAgreement()
    {
        return self::find()
            ->joinWith('agreements')
            ->andWhere([
                '<' , 'agreement.date_from' , strtotime('now')
            ])
            ->andWhere([
                '>' , 'agreement.date_to' , strtotime('now')
            ]);
    }
}
