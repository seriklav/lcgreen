<?php

namespace app\models;
use yii\base\Model;

class SearchMon extends Model {
    public $userto;
    public $sum;
    public function rules()
    {
        return [
            [['login', 'fio', 'sponsor', 'phone', 'status'], 'safe'],
        ];
    }
//    public function attributeLabels()
//    {
//        return [
//            'userto' => 'Получатель',
//            'sum' => 'Сумма',
//        ];
//    }
}