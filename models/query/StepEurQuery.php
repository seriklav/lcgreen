<?php
namespace app\models\query;
use app\models\User;
use app\models\StepEur;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[StepEur]].
 *
 * @see StepQuery
 */

class StepEurQuery extends ActiveQuery
{

    public function getReferals($name)
    {
        $ids = [$name];
        $childrenIds = [$name];
        while ($childrenIds = User::findEur()->select('username')->andWhere(['sponsor' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['sponsor' => array_unique($ids)]);
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
     * @return StepQuery
     */
    public function whereDaily($and = false) {
        if($and) {
            return $this->andWhere([ 'daily_status' => StepEur::DAILY_STATUS]);
        }
        return $this->where([ 'daily_status' => StepEur::DAILY_STATUS]);
    }

    /**
     * @return StepQuery
     */
    public function whereNotDaily($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'daily_status', StepEur::DAILY_STATUS]);
        }
        return $this->where(['!=', 'daily_status', StepEur::DAILY_STATUS]);
    }
    /**
     * @return StepQuery
     */
    public function whereOwner($id = false, $and = false) {
        if(!$id) {
            $id = Yii::$app->user->identity->id;
        }
        if($and) {
            return $this->andWhere(['owner_id' => $id]);
        }
       return $this->where(['owner_id' => $id]);
    }
    /**
     * @return StepEurQuery
     */
    public function whereSponsor($id = false, $and = false) {
        if(!$id) {
            $id = Yii::$app->user->identity->id;
        }
        if($and) {
            return $this->andWhere(['sponsor_id' => $id]);
        }
       return $this->where(['sponsor_id' => $id]);
    }

    public function findStep($step = null, $all = false, $sendQuery = false) {
        $query =  $this->whereOwner()->whereDaily(true);

        if($step) {
            $query->andWhere(['step' => $step]);
        }
        if($sendQuery) {
            return $query;
        }
        if($all) {
            return $query->all();
        }
        return $query->one();
    }

    /**
     * @return StepEurQuery
     */
    public function whereClosures ($and = false) {
        if($and) {
            return $this->andWhere(['status' => StepEur::STATUS_CLOSURES]);
        }
        return $this->where(['status' => StepEur::STATUS_CLOSURES]);
    }
    /**
     * @return StepEurQuery
     */
    public function whereProcessing ($and = false) {
        if($and) {
            return $this->andWhere(['status' => StepEur::STATUS_PROCESSING]);
        }
        return $this->where(['status' => StepEur::STATUS_PROCESSING]);
    }

}