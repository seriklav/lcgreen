<?php
namespace app\models\query;
use app\models\User;
use app\models\StepEur;
use yii\db\ActiveQuery;

class SponsorEurQuery extends ActiveQuery
{

    public function getReferalsOld($name, $daily = false)
    {
        $ids = [$name];
        $childrenIds = [$name];
        while ($childrenIds = User::findEur()->select('username')->andWhere(['sponsor' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['sponsor' => array_unique($ids)]);
    }

    public function getReferals($name, $daily = false)
    {
        $ids = [$name];
        $childrenIds = [$name];
        if($daily) {
            while ($childrenIds = User::findEur()->select('username')->andWhere(['sponsor' => $childrenIds])->whereDaily(true)->column()) {
                $ids = array_merge($ids, $childrenIds);
            }
        } else {
            while ($childrenIds = User::findEur()->where(['sponsor' => $childrenIds])/*->whereNotStep(true)*/->select('username')->column()) {
                $ids = array_merge($ids, $childrenIds);

            }
        }
        $this->andWhere(['sponsor' => array_unique($ids)]);
        if($daily) {
            return $this->whereDaily(true);
        } else {
//            $this->whereNotStep(true);
        }
        return $this;
    }
    public function getSteps($name)
    {
        $ids = [$name];
        $childrenIds = [$name];
        while ($childrenIds = StepEur::find()->select('owner_id')->andWhere(['sponsor_id' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['owner_id' => array_unique($ids)]);
    }

    /**
     * @param bool $and
     * @return SponsorEurQuery
     */
    public function whereNotDailyStep($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'daily_status', StepEur::DAILY_STATUS]);
        }
        return $this->where(['!=', 'daily_status', StepEur::DAILY_STATUS]);
    }

    /**
     * @param bool $and
     * @return SponsorEurQuery
     */
    public function whereNotDaily($and = false) {
        if($and) {
            return $this->andWhere(['or', ['is', 'daily_step_eur', null], ['<', 'daily_step_eur', 1]]);
        }
        return $this->where(['or', ['is', 'daily_step_eur', null], ['<', 'daily_step_eur', 1]]);
    }

    /**
     * @param bool $and
     * @return SponsorEurQuery
     */
    public function whereDaily($and = false) {
        if($and) {
            return $this->andWhere(['and', ['IS NOT', 'daily_step_eur', null], ['>', 'daily_step_eur', 0]]);
        }
        return $this->where(['and', ['IS NOT', 'daily_step_eur', null], ['>', 'daily_step_eur', 0]]);
    }


    /**
     * @param bool $and
     * @return SponsorEurQuery
     */
    public function whereNotStep($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'step', 0]);
        }
        return $this->andWhere(['!=', 'step', 0]);
    }
}