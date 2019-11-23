<?php

namespace app\models;
use yii\base\Model;

class TransferUser extends Model {
    public $userto;
    public $sum;
    public $currency;
    public $emailCode;
    public function rules()
    {
        return [
            [['userto', 'currency', 'sum', 'emailCode'], 'required'],
            [['userto'], 'string','length' => [5,50] ],
            [['sum'],'number','min'=>1]
        ];
    }
    public function attributeLabels()
    {
        return [
	        'currency' => 'Валюта',
            'userto' => 'Получатель',
            'sum' => 'Сумма',
            'emailCode' => 'E-mail код',
        ];
    }
}