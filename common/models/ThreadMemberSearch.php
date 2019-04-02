<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ThreadMember;

/**
 * ThreadMemberSearch represents the model behind the search form of `common\models\ThreadMember`.
 */
class ThreadMemberSearch extends ThreadMember
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'thread_id', 'member_id', 'nickname', 'role'], 'safe'],
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
        $query = ThreadMember::find();

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
        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'thread_id', $this->thread_id])
            ->andFilterWhere(['like', 'member_id', $this->member_id])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'role', $this->role]);

        return $dataProvider;
    }
}
