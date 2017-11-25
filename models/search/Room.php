<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Room as RoomModel;

/**
 * Room represents the model behind the search form about `app\models\Room`.
 */
class Room extends RoomModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_room', 'floor', 'coordinates', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['number_room', 'comment', 'number_bti'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = RoomModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'area_room' => $this->area_room,
            'floor' => $this->floor,
            'coordinates' => $this->coordinates,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'number_room', $this->number_room])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'number_bti', $this->number_bti]);

        return $dataProvider;
    }
}
