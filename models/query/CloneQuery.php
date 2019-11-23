<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\UserClone]].
 *
 * @see \app\models\UserClone
 */
class CloneQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\UserClone[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\UserClone|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
