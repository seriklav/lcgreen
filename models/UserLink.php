<?php
namespace app\models;
use yii\db\ActiveRecord;
class UserLink extends ActiveRecord
{
    public $account;
    public $emailCode;
    public $emailCodeCheck;

    public static function tableName()
    {
        return 'user_link';
    }
    public function rules()
    {
        return [
            [['emailCode', 'account'], 'required'],
            [['link_id','user_id'], 'safe'],
            ['emailCode', 'string', 'length' => [5,5]],
            ['emailCode', 'emailCheckCode'],
            ['account', 'accountCheck']
        ];
    }
    public function attributeLabels()
    {
        return [
            'account' => 'Логин',
            'emailCode' => 'E-mail код'
        ];
    }
    public function accountCheck($attribute)
    {
        $acc = User::findOne(['username' => $this->account]);
        if(is_null($acc))
             return $this->addError($attribute, 'Такого пользователя нет!');
        $check = UserLink::findOne(['link_id' => \Yii::$app->user->identity->id, 'user_id' => $acc->id]);
        if( !is_null($acc) && !is_null($check))  return $this->addError($attribute, 'Ошибка! Данный пользователь уже привязан к аккаунту!');
        if(!is_null($acc))
        {
            $gen = UserLink::findOne(['link_id' => $acc->id]);
            if (!is_null($gen))
                return $this->addError($attribute, 'Ошибка! К указанному пользователю привязаны аккаунты!');
        }
        if(\Yii::$app->user->identity->id == $acc->id)  return $this->addError($attribute, 'Ошибка!');

    }
    public function emailCheckCode($attribute)
    {
        if($this->emailCode != $this->emailCodeCheck)
            $this->addError($attribute, 'Неверный Email код!');
    }
}
