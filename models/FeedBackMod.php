<?php
namespace app\models;
use yii\db\ActiveRecord;
class FeedBackMod extends ActiveRecord
{
    public static function tableName()
    {
        return 'support';
    }
    public function rules()
    {
        return [
            [['title','desc_text','who','send_date','send'], 'safe'],
        ];
    }
    public function CheckUser($attribute)
    {
        if($this->send != -1 && is_null(User::findOne(['username' => $this->send])))
          return $this->addError($attribute, 'Такого пользователя нет!');
    }
}
