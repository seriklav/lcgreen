<?php
namespace app\models;
use yii\db\ActiveRecord;
class DialogFeed extends ActiveRecord
{
    public static function tableName()
    {
        return 'support_dialog';
    }
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['message'], 'string', 'min' => 2 ],
            [['support_id','message','date_ticket'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'message' => 'Сообщение'
        ];
    }
}
