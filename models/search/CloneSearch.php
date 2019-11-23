<?php

namespace app\models\search;

use app\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserClone;

/**
 * DailyMoneySearch represents the model behind the search form of `app\models\DailyMoney`.
 */
class CloneSearch extends UserClone
{
    public $renvestAdmin = false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'number', 'step', 'created_at', 'updated_at', 'status','freeze','renvest', 'status_renvest', 'id'], 'integer'],
            [['name', 'sponsor'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params, $user = false)
    {
        $query = self::find()->orderBy(['id' => SORT_ASC]);
        if($this->renvestAdmin) {
            $query->orderBy(['status_renvest' => SORT_DESC]);
        }
        if($user) {
            $query = $user->getClones()->andWhere(['!=', 'name', $user->username]) ->orderBy(['id' => SORT_ASC]);
        }


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
            'status_renvest' => $this->status_renvest,
            'user_id' => $this->user_id,
            'number' => $this->number,
            'step' => $this->step,
            'freeze' => $this->freeze,
            'renvest' => $this->renvest,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'sponsor', $this->sponsor]);

        return $dataProvider;
    }
}
