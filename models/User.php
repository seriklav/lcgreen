<?php

namespace app\models;
use app\commands\MyClass;
use app\models\query\SponsorDailyEurQuery;
use app\models\query\SponsorDailyQuery;
use app\models\query\SponsorEurQuery;
use app\models\query\SponsorQuery;
use app\models\query\UserEurQuery;
use app\models\query\UserQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
* @property  int $id
* @property  int $daily_step
* @property  int $daily_step_eur
* @property  string $username
* @property  string $sponsor
* @property  UserClone[] $clones
* @property  UserCloneEur[] $clones_eur
* @property  UserClone $clone
* @property  UserCloneEur $clone_eur
* @property  User[] $referrals
* @property  UserClone $activeClone
* @property  UserCloneEur $activeCloneEur
* @property  DailyMoney $dailyStep
* @property  DailyMoneyEur $dailyStepEur
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }
    public $file;
    public $parol;
    public $kurs_eur;
    public $clones_eur;
    public $clone_eur;
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    /**
     * @return string
     */
    public function getSponsorName () {
        $sponsor =  $this->sponsor;
        if($this->username == \Yii::$app->params['parentSponsor']) {
            $sponsor = $this->username;
        }
        if($clone =  $this->clone) {
            $sponsor = $clone->name;
            if($clone->sponsor) {
                $sponsor = $clone->sponsor;
            }
        }
        return $sponsor;
    }
    /**
     * @return string
     */
    public function getSponsorNameEur () {
        $sponsor =  $this->sponsor;
        if($this->username == \Yii::$app->params['parentSponsor']) {
            $sponsor = $this->username;
        }
        if($clone = $this->clone_eur) {
            $sponsor = $clone->name;
            if($clone->sponsor) {
                $sponsor = $clone->sponsor;
            }
        }
        return $sponsor;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferrals() {
        return $this->hasMany(User::className(), ['sponsor' => 'username']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     * @return UserEurQuery
     */
    public static function findEur()
    {
        return new UserEurQuery(get_called_class());
    }

    /**
     * @param $where
     * @return array|ActiveRecord|null
     */
    public static function findOneNotDaily($where) {
        return self::find()->whereNotDaily()->andWhere($where)->one();
    }

    /**
     * @param $where
     * @return array|ActiveRecord|null
     */
    public static function findOneNotDailyEur($where) {
        return self::findEur()->whereNotDaily()->andWhere($where)->one();
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    public static function sponsor()
    {
        return new SponsorQuery(get_called_class());
    }
    public static function sponsorEur()
    {
        return new SponsorEurQuery(get_called_class());
    }

    public static function sponsorDaily()
    {
        return new SponsorDailyQuery(get_called_class());
    }

    public static function sponsorDailyEur()
    {
        return new SponsorDailyEurQuery(get_called_class());
    }
    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDailyStep() {
        return $this->hasOne(DailyMoney::className(), ['id' => 'daily_step']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDailyStepEur() {
        return $this->hasOne(DailyMoneyEur::className(), ['id' => 'daily_step_eur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClones() {
        return $this->hasMany(UserClone::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClonesEur() {
        return $this->hasMany(UserCloneEur::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClone() {
        return $this->hasOne(UserClone::className(), ['user_id' => 'id', 'name' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCloneEur() {
        return $this->hasOne(UserCloneEur::className(), ['user_id' => 'id', 'name' => 'username']);
    }

    /**
     * @return array|ActiveRecord|null
     */
    public function getActiveClone() {
        return $this->getClones()->where(['status' => UserClone::STATUS_ACTIVE])->one();
    }

    /**
     * @return array|ActiveRecord|null
     */
    public function getActiveCloneEur() {
        return $this->getClonesEur()->where(['status' => UserCloneEur::STATUS_ACTIVE])->one();
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUsername($username)
    {
        $row = RegisterForm::find()
            ->where(['username' => $username])
            ->limit(1)->asArray()->all();

        foreach ($row as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    static public function validatePassword($password_form, $password)
    {
        return password_verify($password_form, $password);
    }
    public function attributeLabels()
    {
        return [
            'lc' => 'Баланс LC',
            'eur' => 'Баланс EUR',
            'parol' => 'Сменить пароль',
            'daily_step' => 'daily_step',
            'daily_step_eur' => 'daily_step_eur',
        ];
    }
    public function rules()
    {
        return [
            [['lc','eur','freeze','freeze_eur','email','parol','pass', 'daily_step', 'daily_step_eur'], 'safe'],
        ];
    }
}
