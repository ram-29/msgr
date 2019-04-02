<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ThreadMemberConfig;

/**
 * ThreadMemberConfigSearch represents the model behind the search form of `common\models\ThreadMemberConfig`.
 */
class ThreadMemberConfigSearch extends ThreadMemberConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'thread_id', 'member_id'], 'safe'],
            [['is_muted'], 'integer'],
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
        $query = ThreadMemberConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'is_muted' => $this->is_muted,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'thread_id', $this->thread_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id]);

        return $dataProvider;
    }
}
