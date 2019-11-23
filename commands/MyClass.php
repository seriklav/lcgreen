<?php


namespace app\commands;


use app\models\User;
use app\models\UserClone;
use app\models\UserLogs;

class MyClass
{
    public static function myIp() {
      return self::ips('217.113.21.195');
    }

    public static function ips($myIp = false) {
        $ip = \Yii::$app->request->userIP;
        if($myIp) {
            if($myIp == $ip) {
                return true;
            }
            return false;
        }

        $ips = ['217.113.21.195', '195.54.d43.47', '93.76.193.60'];
        if(in_array($ip, $ips)) {
            return true;
        }
        return false;
    }

    /**
     * @return array|bool
     */
    public static function getTestsUsersIds() {
//        return User::find()->where(['like', 'surname', 'test'])->select('id')->column(); //r.uspeshnaya@yandex.ru
    }

    /**
     * @param int $price
     * @return bool
     */
    public static function testUsersStart($price = 2240) {
        if(!$ids = self::getTestsUsersIds()) {
            return false;
        }
        UserLogs::deleteAll(['user_id' => $ids]);
        UserClone::deleteAll(['user_id' => $ids]);
        User::updateAll(['freeze' => 0, 'lc' =>$price], ['id' => $ids]);
        return true;
    }

    public static function addUsers($end = 10) {
        if(!self::ips('217.113.21.d195')) {
           return false;
        }
        $user = User::findOne(1314);
        for($i = 16; $i< 26; $i++) {
            $u = new  User($user->attributes);
            $u->id = null;
            $u->freeze = 0;
            $u->daily_step = 0;
            $u->lc = 2240;
            $u->name = 'test'.$i;
            $u->surname =$u->name;
            $u->father =$u->name;
            $u->username = 'AA2a73130'.(80+$i);
            $u->sponsor = 'AA2a73130'.(80+($i-1));
            $u ->save();
        }
        return true;
    }

    public static function print_r($data,  $die = true) {
        if(!self::myIp()) {
            return '';
        }
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if($die) {
            die;
        }
    }

}