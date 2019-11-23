<?php

namespace app\models\query;

use app\models\DailyMoneyEur;

/**
 * This is the ActiveQuery class for [[DailyMoneyEur]].
 *
 * @see DailyMoney
 */
class DailyMoneyEurQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DailyMoneyEur[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @return DailyMoneyEurQuery
     */
    public function whereShow() {
        return $this->where(['status' => 1])->orderBy(['range' => SORT_ASC]);
    }
    /**
     * @return DailyMoneyEurQuery
     */
    public function wherePlayground($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoneyEur::CATEGORY_PLAYGROUND]);
        }
        return $this->where(['category' => DailyMoneyEur::CATEGORY_PLAYGROUND]);
    }
    /**
     * @return DailyMoneyEurQuery
     */
    public function wherePlayground_1($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoneyEur::CATEGORY_PLAYGROUND_1]);
        }
        return $this->where(['category' => DailyMoneyEur::CATEGORY_PLAYGROUND_1]);
    }
    /**
     * @return DailyMoneyEurQuery
     */
    public function whereBonuses($and = false) {
        if($and) {
            return $this->andWhere(['category' => DailyMoneyEur::CATEGORY_BONUS]);
        }
        return $this->where(['category' => DailyMoneyEur::CATEGORY_BONUS]);
    }

    /**
     * {@inheritdoc}
     * @return DailyMoneyEur|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
