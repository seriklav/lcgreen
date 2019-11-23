<?php


namespace app\models\statics;

class SiteMonitoring
{
    const STEP_1 = 0;
    const STEP_2 = 1;
    const STEP_3 = 2;
    const STEP_4 = 3;
    const STEP_5 = 4;
    const STEP_6 = 5;
    const STEP_7 = 6;

    public static $step_keys = [
        self::STEP_1,
        self::STEP_2,
        self::STEP_3,
        self::STEP_4,
        self::STEP_5,
        self::STEP_6,
        self::STEP_7
    ];
    public static function getStepKeys($offset = 0, $limit = false) {
        $data = self::$step_keys;
        if(!$limit) {
            $limit = count($data)- $offset;
        }
        return array_slice($data, $offset, $limit);
    }
    public static $step_name = [
        self::STEP_1 => '1',
        self::STEP_2 =>'2',
        self::STEP_3 =>'3',
        self::STEP_4 => '4',
        self::STEP_5 => '5',
        self::STEP_6 => '6',
        self::STEP_7 => '7'
    ];

    public static  $step_price = [
        self::STEP_1 => 70,
        self::STEP_2 =>140,
        self::STEP_3 => 280,
        self::STEP_4 => 560,
        self::STEP_5 => 1120,
        self::STEP_6 => 2240,
        self::STEP_7 => 4480
        ];
    public static  $step_place = [
        self::STEP_1 => 3,
        self::STEP_2 => 3,
        self::STEP_3 => 3,
        self::STEP_4 => 3,
        self::STEP_5 => 3,
        self::STEP_6 => 3,
        self::STEP_7 =>3
    ];
    public static  $step_give = [
        self::STEP_1 => 70,
        self::STEP_2 =>140,
        self::STEP_3 => 280,
        self::STEP_4 => 560,
        self::STEP_5 => 1120,
        self::STEP_6 => 2240,
        self::STEP_7 => 4480
    ];
    public static  $step_freeze = [
        self::STEP_1 => 70,
        self::STEP_2 => 280,
        self::STEP_3 => 560,
        self::STEP_4 => 1120,
        self::STEP_5 => 2240,
        self::STEP_6 => 4480,
        self::STEP_7 => 8960
    ];

}