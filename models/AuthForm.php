<?php

use yii\db\ActiveRecord;

class AuthForm extends  ActiveReCord {
    public static  function tableName()
    {
        return 'user';
    }
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'surname', 'sponsor', 'sex', 'dateBirth', 'country', 'region', 'city', 'email','emailRepeat', 'pass','passRepeat','EmailCode'], 'required'],
            [['name', 'surname', 'sponsor', 'sex', 'dateBirth', 'country', 'region', 'city', 'email','father', 'pass','phone','skype'], 'safe'],
            ['agree', 'required', 'requiredValue' => 1, 'message' => 'Вы должны согласиться с условиями!'],
            // email has to be a valid email address
            ['email', 'email'],
            [['pass','passRepeat'], 'string','length' => [6,50] ],
            ['passRepeat', 'compare', 'compareAttribute'=> 'pass', 'message' => "Пароли не совпадают!"],
            ['emailRepeat', 'compare', 'compareAttribute'=> 'email', 'message' => "Email не совпадают!"],
            ['EmailCode', 'string','length' => [5,5]],
        ];
    }

}