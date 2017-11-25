<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "room_coordinates".
 *
 * @property integer $id
 * @property integer $floor
 * @property string $coordinates
 */
class RoomCoordinates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'room_coordinates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['floor', 'coordinates'], 'required'],
            [['floor'], 'integer'],
            [['coordinates'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'floor' => 'Floor',
            'coordinates' => 'Coordinates',
        ];
    }

    public function getA2q()
    {
        return $this
            ->hasMany(Agreement2quarter::className(),['quarter_id' => 'id'])
            ->via('room');
    }
    // список договоров
    public function getAgreements()
    {
        return $this
            ->hasMany(Agreement::className(),['id' => 'agreement_id'])
            ->via('a2q');

    }

    // офис
    public function getRoom()
    {
        return $this->hasOne(Room::className(),['coordinates' => 'id']);
    }

    // активный договор за помещением
    public function activeAgreement()
    {
        $agreements = $this->agreements;
        $isActive = false;
        foreach ($agreements as $agreement)
            if ($agreement->isActive() && ($agreement->ten->type_tenant == Tenant::TYPE_TENANT_ACTUAL)) $isActive = true;
        return $isActive;
    }
    // договор с предупреждением
    public function warningAgreement()
    {
        $agreements = $this->agreements;
        $isWarning = false;
        foreach ($agreements as $agreement)
            if ($agreement->isWarning() && ($agreement->ten->type_tenant == Tenant::TYPE_TENANT_ACTUAL)) $isWarning = true;
        return $isWarning;
    }
    // наличие юридических адресов
    public function hasCountActiveLegal()
    {
        $agreements = $this->agreements;
        $hasLegal = 0;
        foreach ($agreements as $agreement) {
            if (
                $agreement->date_from <= strtotime('now') &&
                $agreement->date_to >= strtotime('now') &&
                $agreement->ten->type_tenant == Tenant::TYPE_TENANT_LEGAL
            )
                $hasLegal++;
        }
        return $hasLegal;
    }
}
