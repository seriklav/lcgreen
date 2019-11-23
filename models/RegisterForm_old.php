<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;
class RegisterForm extends ActiveReCord
{
   /* public $name;
    public $surname;
    public $father;
    public $sponsor;
    public $sex;
    public $dateBirth;
    public $country;
    public $region;
    public $city;
    public $email;
    public $phone;
    public $skype;
    public $emailRepeat;
    public $pass;
    public $passRepeat;
    public $EmailCode;
    public $agree;*/

    public $emailRepeat;
    public $passRepeat;
    public $EmailCode;
    public $agree;

    public static  function tableName()
    {
        return 'user';
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'surname', 'sponsor', 'sex', 'dateBirth', 'country', 'region', 'city', 'email','emailRepeat', 'pass','passRepeat','EmailCode'], 'required'],
            [['name', 'surname', 'sponsor', 'sex', 'dateBirth', 'country', 'region', 'city', 'email','father', 'pass','phone','skype'], 'safe'],
            ['agree', 'required', 'requiredValue' => 1, 'message' => 'Вы должны согласиться с условиями!'],
            // email has to be a valid email address
            ['email', 'email'],
            ['sponsor', 'sponsorCheck'],
            [['pass','passRepeat'], 'string','length' => [6,50] ],
            ['passRepeat', 'compare', 'compareAttribute'=> 'pass', 'message' => "Пароли не совпадают!"],
            ['emailRepeat', 'compare', 'compareAttribute'=> 'email', 'message' => "Email не совпадают!"],
            ['EmailCode', 'string','length' => [5,5]],
        ];
    }
    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'E-mail',
            'surname' => 'Фамилия',
            'sex' => 'Пол',
            'dateBirth' => 'Дата рождения',
            'country' => 'Страна',
            'region' => 'Регион',
            'city' => 'Город',
            'emailRepeat' => 'Повтор Email',
            'pass' => 'Пароль',
            'passRepeat' => 'Повтор пароля',
            'EmailCode' => 'Email код',
            'agree' => 'Согласны с условиями',
            'sponsor' => 'Cпонсор'
        ];
    }
    public function sponsorCheck($attribute, $params)
    {
        if(!RegisterForm::find()->where(['username'=> $this->sponsor])->exists())
            $this->addError($attribute, 'Такого спонсора нет!');
    }
}