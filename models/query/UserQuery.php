<?php
namespace app\models\query;
use app\models\User;
use app\models\Step;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{


    /**
     * @param bool $and
     * @return UserQuery
     */
    public function whereNotDaily($and = false) {
        if($and) {
            return $this->andWhere(['or', ['is', 'daily_step', null], ['<', 'daily_step', 1]]);
        }
        return $this->where(['or', ['is', 'daily_step', null], ['<', 'daily_step', 1]]);
    }

    /**
     * @param bool $and
     * @return UserQuery
     */
    public function whereDaily($and = false) {
        if($and) {
            return $this->andWhere(['and', ['IS NOT', 'daily_step', null], ['>', 'daily_step', 0]]);
        }
        return $this->where(['and', ['IS NOT', 'daily_step', null], ['>', 'daily_step', 0]]);
    }

    /**
     * @param bool $and
     * @return UserQuery
     */
    public function whereNotStep($and = false) {
        if($and) {
            return $this->andWhere(['!=', 'step', 0]);
        }
        return $this->andWhere(['!=', 'step', 0]);
    }
}