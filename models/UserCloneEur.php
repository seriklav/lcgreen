<?php

namespace app\models;

use app\commands\MyClass;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%clone_eur}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $number
 * @property int $step
 * @property string $name
 * @property string $sponsor
 * @property int $freeze
 * @property int $renvest
 * @property int $status_renvest
 * @property int $status
 * @property int $date_renvest
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property StepEur $stepOne
 * @property StepEur[] $steps
 * @property UserCloneEur[] $cloneSubs
 * @property UserCloneEur $cloneParent
 */
class UserCloneEur extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_PASSIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%clone_eur}}';
    }

    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    public function checkCloneBoll() {
        /**@var $user \app\models\User */
        if(!$user = Yii::$app->user->identity) {
            return false;
        }
        if($this->name !=  $user->username && $this->user_id == $user->id) {
            return true;
        }
        return false;
    }

    /**
     * @return $this|UserCloneEur|array|null
     */
    public function checkClone() {
        $model = UserCloneEur::find()->where([
            'sponsor' => $this->sponsor,
            'user_id' => $this->user_id,
        ])->andWhere(['!=', 'name',  $this->sponsor])->one();
            if($model) {
            $model->number = $this->number;
            $model->update();
            return $model;
        }
        return $this;
    }


    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function createActive() {
        UserCloneEur::updateAll(['status' => self::STATUS_PASSIVE], ['user_id' => $this->user_id]);
        $this->status = UserCloneEur::STATUS_ACTIVE;
        $this->update();
        return true;
    }

    /**
     * @param $user User
     * @return UserCloneEur
     */
    public static function createClone(User $user) {
        if($modelClone = $user->activeCloneEur) {
            return $modelClone;
        }
        $modelClone = new UserCloneEur();
        $modelClone->user_id = $user->id;
        $modelClone->step = $user->dailyStepEur? $user->dailyStepEur->id : null;
        $modelClone->status = self::STATUS_ACTIVE;
        $modelClone->name = $user->username;
        if($modelClone->save()) {
            return $modelClone;
        }
        return false;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'number', 'step', 'created_at', 'updated_at', 'status', 'freeze', 'renvest','status_renvest', 'date_renvest'], 'integer'],
            [['name', 'sponsor'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'number' => Yii::t('app', 'Number'),
            'step' => Yii::t('app', 'Step'),
            'name' => Yii::t('app', 'название'),
            'status' => Yii::t('app', 'Status'),
            'sponsor' => Yii::t('app', 'Sponsor'),
            'renvest' => Yii::t('app', 'Реинвест'),
            'status_renvest' => Yii::t('app', 'Статус Ренвест'),
            'date_renvest' => Yii::t('app', 'Дата Ренвест'),
            'freeze' => Yii::t('app', 'Замороженная сумма'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStepOne($step = false)
    {
        if(!$step) {
            $step = Yii::$app->user->identity->daily_step;
        }
        $query =  $this->hasOne(StepEur::className(), ['clone' => 'id']);
        if($step) {
           $query->where(['step' => $step]);
        }
        return $query;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(StepEur::className(), ['clone' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function GetCloneSubs() {
        return $this->hasMany(UserCloneEur::className(), ['sponsor' => 'name'])->where(['!=', 'id', $this->id]);
    }

    public function getClones( $clones = []) {
        $clone = $this->getCloneSubs()->where(['user_id' => $this->user_id])->one();
        if($clone) {
            $clones [] = $clone;
            return $clone->getClones($clones);
        }
       return $clones;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function GetCloneParent() {
        return $this->hasOne(UserCloneEur::className(), ['name' => 'sponsor']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\CloneEurQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CloneEurQuery(get_called_class());
    }


    public function createStep(DailyMoneyEur $number, StepFormEur $stepForm, $clone = false) {

        $stepForm->checkSponsor();
        if (!$sponsorClone = UserCloneEur::findOne(['name' => $stepForm->sponsor])) {
            $user = User::findOne(['username' => $stepForm->sponsor]);
            $sponsorClone = $user->clone_eur;
        }
        if (!$sponsorClone) {
            return ['message' => 'Указанный спонсор не состоит в площадке!'];
        }
        if($sponsorClone->id == $this->id ) {
            //создание цикла пользователя
            $step = new StepEur();
            $step->owner_id = $this->user_id;
            $step->step = $number->id;
            $step->daily_status = StepEur::DAILY_STATUS;
            $step->clone = $this->id;
            $step->sponsor = $stepForm->sponsor;
            $step->sponsor_id = $sponsorClone->user_id;
            if($step->save()) {
                $model = $this->user;
                $model->eur -= $number->price;
                $this->freeze = 0;
                $model->daily_step_eur = $number->id;
                $model->save();

                $action = UserLogs::startAction($number->range);
                if($this->name != $model->username) {
                    $action = UserLogs::addCloneAction(1, $number->range);
                }
                UserLogs::addLog(Yii::$app->user->identity->id, -$number->price, $action);
            }


            $this->sponsor = $sponsorClone->name;
            $this->save();
            return true;
        }

        if (!$sponsorStep = $sponsorClone->getStepOne($number->id)->one()) {
            return ['message' => 'Указанный спонсор не состоит в площадке!'];
        }

        if ($sponsorStep->status == Step::STATUS_CLOSURES) {
            return ['message' => 'У данного спонсора указанный площадке уже закрыт!'];
        }

        //создание цикла пользователя
        $step = new StepEur();
        $step->owner_id = $this->user_id;
        $step->step = $number->id;
        $step->daily_status = StepEur::DAILY_STATUS;
        $step->clone = $this->id;
        $step->sponsor = $stepForm->sponsor;
        $step->sponsor_id = $sponsorClone->user_id;
        if($step->save()) {
            $this->freeze = 0;
            if(!$this->save()) {
//                MyClass::print_r([$this->errors, 1]);
            }

            $model = $this->user;
            $model->eur-= $number->price;
            $model->daily_step_eur = $number->id;
            $model->freeze = $model->getClones()->sum('freeze');
            $model->save();
            $action = UserLogs::startAction($number->range);
            if($this->name != $model->username) {
                $action = UserLogs::addCloneAction(1, $number->range);
            }
            UserLogs::addLog(Yii::$app->user->identity->id, -$number->price, $action);
        }


        $this->sponsor = $sponsorClone->name;
        if(!$this->save()) {
//            MyClass::print_r([$this->errors,'125687']);
        }

        $sevStep = new StepFormEur();
        $sevStep->user_id = $this->user_id;
        $sevStep->step_id = $sponsorStep->id;
        $sevStep->sponsor_id = $sponsorClone->id;
        $sevStep->sponsor = 'Seven';
        $sevStep->daily_status = StepEur::DAILY_STATUS;
        $sevStep->seven = $sponsorStep->owner_id;
        $sevStep->save(false);
        $check = $sponsorStep->GetStepsUsers($sponsorClone->user_id)->count();
        $userSponser = $sponsorClone->user;
        if($number->bonus) {
            $userSponser->eur+= $number->bonus;
            UserLogs::addLog($userSponser->id, $number->bonus, 'Бонус от площадки '.$number->range);
        }

        if ($check == $number->place) {

            $sponsorStep->status = StepEur::STATUS_CLOSURES;
            $sponsorStep->save();
            $sponsorClone->freeze = $number->freeze;

            $maxStep = $sponsorClone->getSteps()->whereClosures()->orderBy(['step' => SORT_DESC])->select(['id', 'step'])->one();
            if($sponsorClone->name != $sponsorClone->user->username) {
                $maxDaily = DailyMoneyEur::getMaxDaily();
                if($maxStep && $maxStep->step == $maxDaily->id) {
                    $sponsorClone->freeze = 0;
                    if($maxDaily->renvest) {
                        $sponsorClone->freeze =$maxDaily->renvest;
                        $sponsorClone->renvest =$maxDaily->renvest;
                        $sponsorClone->date_renvest = time();
                        $sponsorClone->status_renvest = 1;
                    }
                }
            } else {
                $maxDaily = DailyMoneyEur::getMaxDaily(true);
                if($maxStep && $maxStep->step == $maxDaily->id) {
                    if($maxDaily->renvest) {
                        $sponsorClone->freeze+=$maxDaily->renvest;
                        $sponsorClone->renvest =$maxDaily->renvest;
                        $sponsorClone->date_renvest = time();
                        $sponsorClone->status_renvest = 1;
                    }
                }
            }


            if(!$sponsorClone->save()) {
//                MyClass::print_r([$sponsorClone->errors, '12']);
            }
            $give = $number->give;
            if($sponsorClone->name != $sponsorClone->user->username && $number->clone_give) {
                $give = $number->clone_give;
            }
            $userSponser->lc+= $give;
            UserLogs::addLog($sponsorClone->user_id, $give, UserLogs::closedAction($number->range));
        }
        if(!$userSponser->save()) {
//            MyClass::print_r([$userSponser->errors, '125']);
        }
        return [];
    }

}
