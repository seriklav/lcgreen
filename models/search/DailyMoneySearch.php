<?php

namespace app\models\search;

use app\commands\MyClass;
use app\models\DailyMoneyEur;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DailyMoney;
use yii\helpers\ArrayHelper;

/**
 * DailyMoneySearch represents the model behind the search form of `app\models\DailyMoney`.
 */
class DailyMoneySearch extends DailyMoney
{
	public $type = '';

	/**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'price', 'place', 'give', 'clone_give', 'freeze', 'status', 'range',  'created_at', 'bonus', 'renvest'], 'integer'],
            [['name'], 'safe'],
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
    	switch ($this->type) {
		    case 'eur':
			    $query = DailyMoneyEur::find();
			    break;
		    default:
			    $query = DailyMoney::find();
			    break;
	    }


        if(!ArrayHelper::getValue($params, 'sort')) {
            $query->orderBy(['range' => SORT_ASC]);
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
            'price' => $this->price,
            'range' => $this->range,
            'place' => $this->place,
            'give' => $this->give,
            'clone_give' => $this->clone_give,
            'freeze' => $this->freeze,
            'renvest' => $this->renvest,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'bonus' => $this->bonus,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
