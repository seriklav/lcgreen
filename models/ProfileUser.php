<?php
namespace app\models;
use yii\db\ActiveRecord;
class ProfileUser extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_setting';
    }
    public function rules()
    {
        return [
            [['user_id','fio_vis','phone_vis','email_vis','skype_vis','phone_edit','email_edit','skype_edit'], 'safe'],
        ];
    }
}
