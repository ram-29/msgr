<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ThreadMessageSeen;

/**
 * ThreadMessageSeenSearch represents the model behind the search form of `common\models\ThreadMessageSeen`.
 */
class ThreadMessageSeenSearch extends ThreadMessageSeen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'thread_message_id', 'member_id', 'seen_at'], 'safe'],
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
        $query = ThreadMessageSeen::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'seen_at' => $this->seen_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'thread_message_id', $this->thread_message_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id]);

        return $dataProvider;
    }
}
