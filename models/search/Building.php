<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Building as BuildingModel;

/**
 * Building represents the model behind the search form about `app\models\Building`.
 */
class Building extends BuildingModel
{
    public $address;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'house', 'building', 'letter', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['city', 'street', 'coordinates', 'address'], 'safe'],
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

    public function attributeLabels()
    {
        $main = parent::attributeLabels();
        $main['address'] = 'Адрес здания';
        return $main;
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
        $query = BuildingModel::find();

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

        if ($this->address){
            $query
                ->orFilterWhere(['like', 'city', $this->address])
                ->orFilterWhere(['like', 'street', $this->address])
                ->orFilterWhere(['like', 'house', $this->address])
                ->orFilterWhere(['like', 'building', $this->address])
                ->orFilterWhere(['like', 'letter', $this->address]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }
}
