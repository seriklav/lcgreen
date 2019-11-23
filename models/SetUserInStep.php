<?php
namespace app\models;

use yii\base\Model;

class SetUserInStep extends Model
{
    public $username;
    public function rules()
    {
        return [
            [['username'], 'required'],
            ['username', 'sponsorCheck']
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Логин пользователя'
        ];
    }
    public function sponsorCheck($attribute)
    {
        $spons = User::findOne(['username' => $this->username]);
        if(is_null($spons))
            $this->addError($attribute, 'Такого спонсора нет!');
    }
}