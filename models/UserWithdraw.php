<?php

namespace app\models;
use yii\db\ActiveRecord;

class UserWithdraw extends  ActiveReCord {
    public static  function tableName()
    {
        return 'withdraw';
    }
    public function rules()
    {
        return [
            [['type', 'currency', 'wallet', 'sum'], 'required'],
            [['wallet'], 'string','length' => [5,50] ],
            [['sum'],'number','min'=>100],
            [['user_id','type','wallet','sum'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
        	'currency' => 'Валюта',
            'wallet' => 'Номер счета',
            'sum' => 'Сумма',
        ];
    }
}