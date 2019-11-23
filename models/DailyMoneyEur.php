<?php

namespace app\models;

use app\models\query\DailyMoneyEurQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%daily_money_eur}}".
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $place
 * @property int $give
 * @property int $clone_give
 * @property int $freeze
 * @property int $status
 * @property int $range
 * @property int $bonus
 * @property int $category
 * @property int $renvest
 * @property int $created_at
 * @property int $updated_at
 */
class DailyMoneyEur extends \yii\db\ActiveRecord
{
    const CATEGORY_PLAYGROUND = 0;
    const CATEGORY_PLAYGROUND_1 = 2;
    const CATEGORY_BONUS = 1;
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%daily_money_eur}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'place', 'give', 'clone_give', 'freeze', 'status', 'range', 'created_at', 'updated_at', 'category', 'bonus', 'renvest'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название'),
            'price' => Yii::t('app', 'Вступление'),
            'place' => Yii::t('app', 'Место'),
            'give' => Yii::t('app', 'Выход'),
            'clone_give' => Yii::t('app', 'Выход для  клоне '),
            'freeze' => Yii::t('app', 'Замороженная сумма'),
            'status' => Yii::t('app', 'Статус'),
            'category' => Yii::t('app', 'Category'),
            'range' => Yii::t('app', 'Спектр'),
            'bonus' => Yii::t('app', 'Бонус'),
            'renvest' => Yii::t('app', 'Ренвест'),
            'created_at' => Yii::t('app', 'Создан в'),
            'updated_at' => Yii::t('app', 'Обновлен в'),
        ];
    }

    public static function getMaxDaily($bonus = false) {
        $query  =  self::find()->whereShow();
        if($bonus) {
            $query->andWhere(['=', 'category', self::CATEGORY_BONUS]);
        } else{
            $query->andWhere(['!=', 'category', self::CATEGORY_BONUS]);
        }
        return $query->orderBy(['range'=> SORT_DESC])->one();
    }
    public static function getMinDaily($bonus = false) {
        $query  =  self::find()->whereShow();
        if($bonus) {
            $query->andWhere(['=', 'category', self::CATEGORY_BONUS]);
        } else{
            $query->andWhere(['!=', 'category', self::CATEGORY_BONUS]);
        }
        return $query->orderBy(['range'=> SORT_ASC])->one();
    }

    public function getAllMin($sum = false) {
        $price = 0;
        $dailyes = DailyMoneyEur::find()->whereShow()->indexBy('id')->all();
        $data = false;

        foreach (ArrayHelper::getColumn($dailyes, 'range') as $i=>$value) {
            if($value <= $this->range) {
                if($sum) {
                    $price+= $this->price;
                } else {
                    $data[$i] = $dailyes[$i];
                }
            }
        }
       /* if($this->category == DailyMoney::CATEGORY_PLAYGROUND_1) {
        } else {
            $data[$this->id] = $dailyes[$this->id];
        }*/
        if($sum) {
            return $price;
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     * @return DailyMoneyEurQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DailyMoneyEurQuery(get_called_class());
    }

    public static function getStatus() {
        $data = [
            1 => 'показать',
            0 => 'спрятать',
        ];
        return $data;
    }

    public static function getCategory() {
        $data = [
            self::CATEGORY_PLAYGROUND => 'площадка',
            self::CATEGORY_PLAYGROUND_1 => 'купить сразу',
            self::CATEGORY_BONUS => 'бонус'
        ];
        return $data;
    }
}
