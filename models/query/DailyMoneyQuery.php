<?php

namespace app\models\query;

use app\models\DailyMoney;

/**
 * This is the ActiveQuery class for [[DailyMoney]].
 *
 * @see DailyMoney
 */
class DailyMoneyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DailyMoney[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @return DailyMoneyQuery
     */
    public function whereShow() {
        return $this->where(['status' => 1])->orderBy(['range' => SORT_ASC]);
    }
    /**
     * @return DailyMoneyQuery
     */
    public function wherePlayground($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoney::CATEGORY_PLAYGROUND]);
        }
        return $this->where(['category' => DailyMoney::CATEGORY_PLAYGROUND]);
    }
    /**
     * @return DailyMoneyQuery
     */
    public function wherePlayground_1($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoney::CATEGORY_PLAYGROUND_1]);
        }
        return $this->where(['category' => DailyMoney::CATEGORY_PLAYGROUND_1]);
    }
    /**
     * @return DailyMoneyQuery
     */
    public function whereBonuses($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoney::CATEGORY_BONUS]);
        }
        return $this->where(['category' => DailyMoney::CATEGORY_BONUS]);
    }

    /**
     * {@inheritdoc}
     * @return DailyMoney|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
