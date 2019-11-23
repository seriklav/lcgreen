<?php
namespace app\models\query;
use app\models\User;
use app\models\Step;
use yii\db\ActiveQuery;

class SponsorDailyQuery extends ActiveQuery
{

    public function getReferals($name)
    {
        $ids = [$name];
        $childrenIds = [$name];
        while ($childrenIds = User::find()->select('username')->andWhere(['sponsor' => $childrenIds])->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['sponsor' => array_unique($ids)]);
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
}