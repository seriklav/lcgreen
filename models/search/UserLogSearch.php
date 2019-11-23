<?php

namespace app\models\search;

use app\models\UserLogs;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * UserLogSearch represents the model behind the search form of `app\models\UserLog`.
 */
class UserLogSearch extends UserLogs
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sum'], 'integer'],
            [['action', 'date'], 'safe'],
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
    public function search($params)
    {
        $query = UserLogs::find()->where(['user_id' => Yii::$app->user->identity->id,'type' => 0]);

        if(!ArrayHelper::getValue($params, 'sort')) {
            $query->orderBy(['id' => SORT_DESC, 'date' => SORT_DESC]);
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

        $query->andFilterWhere(['like', 'id', $this->id]);
        $query->andFilterWhere(['like', 'sum', $this->sum]);
        $query->andFilterWhere(['like', 'date', $this->date]);
        $query->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
}
