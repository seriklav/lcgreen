<?php

namespace app\models;
use yii\db\ActiveRecord;

class UserLogs extends  ActiveReCord {
    public static  function tableName()
    {
        return 'user_logs';
    }
    public function rules()
    {
        return [
            [['user_id','date','sum','type','curent_lc','curent_eur','detail','object','action'], 'safe'],
        ];
    }
    public static function closedAction($count) {
        return 'Бонус от закрытия
          '.$count.' площадки ';
    }
    public static function addCloneAction($count, $number = 1) {
        return "Покупка  $count клона на $number площадке";
    }

   public static function startAction($number) {
        return "Вступление на $number площадку";
    }

    public static function addLog($user_id, $price, $action) {
        //логирование
        $logs = new UserLogs();
        $logs->type = 0;
        $logs->user_id = $user_id;
        $logs->date = date("Y-m-d H:i:s");
        $logs->object = 'Счёт';
        $logs->action = $action;
        $logs->sum = $price;
        $logs->save();
    }
}