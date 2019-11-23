<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'name', 'surname', 'father'],'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
                ->select(['id', 'username', 'name', 'surname', 'lc', 'eur','dateReg']),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'surname', $this->surname])
        ->andFilterWhere(['like', 'father', $this->father]);

        return $dataProvider;
    }
}