<?php
namespace app\models;

use app\commands\MyClass;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * @var $daily_status integer
 */
class StepFormEur extends ActiveRecord
{

	public $emailCode;
	public $emailCodeCheck;

	public static  function tableName()
	{
		return 'step_users_eur';
	}
	public function rules()
	{
		return [
			[['emailCode', 'sponsor'], 'required'],
			[['sponsor'], 'safe'],
			[['daily_status'], 'integer'],
			['emailCode', 'string', 'length' => [5,5]],
			['emailCode', 'emailCheckCode'],
			['sponsor', 'sponsorCheck']
		];
	}

	public function checkSponsor() {
		$sponsor = str_replace(' ', '', $this->sponsor);
		$spons = str_replace("AA", "", $sponsor);
		$sponsor = mb_strtolower ( $spons );
		$sponsor = 'AA'.$sponsor;
		$this->sponsor = $sponsor;
	}

	public function attributeLabels()
	{
		return [
			'sponsor' => 'Логин',
			'emailCode' => 'E-mail код',
			'daily_status' => 'Daily Status',
		];
	}
	public function sponsorCheck($attribute)
	{
		$spons = User::findOne(['username' => $this->sponsor]);
		if($this->daily_status = StepEur::DAILY_STATUS) {
			$sponClone = UserCloneEur::findOne(['name' => $this->sponsor]);
			if(is_null($spons) && is_null($sponClone))
				$this->addError($attribute, 'Такого спонсора нет!');

		} else {
			if(is_null($spons))
				$this->addError($attribute, 'Такого спонсора нет!');
		}

	}
	public function emailCheckCode($attribute)
	{
		if(MyClass::myIp()) {
			return true;
		}
		if($this->emailCode != $this->emailCodeCheck)
			$this->addError($attribute, 'Неверный Email код!');
	}
}