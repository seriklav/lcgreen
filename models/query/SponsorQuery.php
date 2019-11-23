<?php
namespace app\models\query;
use app\models\User;
use app\models\Step;
use yii\db\ActiveQuery;

class SponsorQuery extends ActiveQuery
{

    public function getReferalsOld($name, $daily = false)
    {
        $ids = [$name];
        $childrenIds = [$name];
        while ($childrenIds = User::find()->select('username')->andWhere(['sponsor' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['sponsor' => array_unique($ids)]);
    }

    public function getReferals($name, $daily = false)
    {
        $ids = [$name];
        $childrenIds = [$name];
        if($daily) {
            while ($childrenIds = User::find()->select('username')->andWhere(['sponsor' => $childrenIds])->whereDaily(true)->column()) {
                $ids = array_merge($ids, $childrenIds);
            }
        } else {
            while ($childrenIds = User::find()->where(['sponsor' => $childrenIds])/*->whereNotStep(true)*/->select('username')->column()) {
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
        while ($childrenIds = Step::find()->select('owner_id')->andWhere(['sponsor_id' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['owner_id' => array_unique($ids)]);
    }

    /**
     * @param bool $and
     * @return SponsorQuery
     */
    public function whereNotDailyStep($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'daily_status', Step::DAILY_STATUS]);
        }
        return $this->where(['!=', 'daily_status', Step::DAILY_STATUS]);
    }

    /**
     * @param bool $and
     * @return SponsorQuery
     */
    public function whereNotDaily($and = false) {
        if($and) {
            return $this->andWhere(['or', ['is', 'daily_step', null], ['<', 'daily_step', 1]]);
        }
        return $this->where(['or', ['is', 'daily_step', null], ['<', 'daily_step', 1]]);
    }

    /**
     * @param bool $and
     * @return SponsorQuery
     */
    public function whereDaily($and = false) {
        if($and) {
            return $this->andWhere(['and', ['IS NOT', 'daily_step', null], ['>', 'daily_step', 0]]);
        }
        return $this->where(['and', ['IS NOT', 'daily_step', null], ['>', 'daily_step', 0]]);
    }


    /**
     * @param bool $and
     * @return SponsorQuery
     */
    public function whereNotStep($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'step', 0]);
        }
        return $this->andWhere(['!=', 'step', 0]);
    }
}