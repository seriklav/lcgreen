<?php
namespace app\models;
use yii\db\ActiveRecord;
class AdmSetting extends ActiveRecord
{
    public static function tableName()
    {
        return 'setting';
    }
    public function rules()
    {
        return [
            [['kurs','kurs_eur','perfect','perfect_eur','perfect_key'], 'safe'],
        ];
    }
}
