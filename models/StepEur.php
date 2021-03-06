<?php
namespace app\models;
use app\models\query\StepEurQuery;
use yii\db\ActiveRecord;
use app\models\query\SponsorEurQuery;

/**
 * @property  $daily_status integer
 * @property  integer|null $clone
 * @property  UserCloneEur $cloneOne
 * @property  DailyMoneyEur $daily
 * @property  StepEur[] $sponsors
 * @property  StepUsersEur[] $stepsUsers
 */
class StepEur extends ActiveRecord
{
	const DAILY_STATUS = 1;
	const STATUS_PROCESSING  = 0;
	const STATUS_CLOSURES  = 1;
	public static function tableName()
	{
		return 'step_eur';
	}
	public function rules()
	{
		return [
			[['user_id','step', 'daily_status'], 'safe'],
			[['clone'], 'integer'],
		];
	}
	public static function stepka()
	{
		return new SponsorEurQuery(get_called_class());
	}


	public static function findOneNotDaily($where)
	{
		return self::find()->whereNotDaily()->andWhere($where)->one(); // TODO: Change the autogenerated stub
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCloneOne() {
		return $this->hasOne(UserCloneEur::className(), ['id' => 'clone']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDaily() {
		return $this->hasOne(DailyMoneyEur::className(), ['id' => 'step']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSponsors() {
		return $this->hasMany(StepEur::className(), ['sponsor_id' => 'owner_id'])->andWhere(['!=', 'owner_id', $this->owner_id]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSponsorss() {
		return $this->hasMany(StepEur::className(), ['owner_id' => 'sponsor_id'])->andWhere(['!=', 'sponsor_id', $this->sponsor_id]);
	}

	/**
	 * @param bool $userId
	 * @return \yii\db\ActiveQuery
	 */
	public function GetStepsUsers($userId = false) {
		if(!$userId) {
			$userId = \Yii::$app->user->id;
		}
		return $this->getStepsUsersQuery()->andWhere(['seven' => $userId]);
	}

	public function getStepsUsersQuery() {
		return $this->hasMany(StepUsersEur::className(), ['step_id' => 'id']);
	}


	/**
	 * {@inheritdoc}
	 * @return StepEurQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new StepEurQuery(get_called_class());
	}
}
