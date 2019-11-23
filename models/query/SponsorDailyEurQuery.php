<?php
namespace app\models\query;
use app\models\StepEur;
use app\models\User;
use yii\db\ActiveQuery;

class SponsorDailyEurQuery extends ActiveQuery
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
}