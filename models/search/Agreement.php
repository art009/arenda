<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agreement as AgreementModel;

/**
 * Agreement represents the model behind the search form about `app\models\Agreement`.
 */
class Agreement extends AgreementModel
{
    public $building;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date_from', 'date_to', 'created_at', 'updated_at', 'created_by', 'updated_by', 'building'], 'integer'],
            [['tenant', 'quarter'],'safe'],
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
        $query = AgreementModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date_to'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->tenant){
            $query
                ->joinWith('ten', false)
                ->andFilterWhere(['like', 'tenant.name', $this->tenant]);
        }
        if ($this->building){
            $query
                ->joinWith('build', false)
                ->andFilterWhere(['building.id' => $this->building]);
        }
        if ($this->quarter){
            $query
                ->joinWith('quarter', false)
                ->andFilterWhere(['room.number_room' => $this->quarter]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
