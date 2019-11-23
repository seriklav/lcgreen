<?php

namespace app\models;
use yii\db\ActiveRecord;

class UserNews extends  ActiveReCord {
    public static  function tableName()
    {
        return 'news';
    }
    public function rules()
    {
        return [
            [['title','desc_text'], 'safe'],
        ];
    }
}