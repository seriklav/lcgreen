<?php

namespace app\models;

use yii\db\ActiveRecord;

class StepInfo extends  ActiveReCord {
    public static  function tableName()
    {
        return 'step';
    }
}