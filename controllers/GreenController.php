<?php

namespace app\controllers;

use app\commands\MyClass;
use app\models\UserClone;
use app\models\UserCloneEur;
use yii\base\Security;
use yii\bootstrap\ActiveForm;
use app\models\CountrySelect;
use app\models\CitySelect;
use app\models\RegionSelect;
use app\models\RegisterForm;
use app\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\ContactForm;
use yii\web\Response;
use yii\helpers\Url;

use app\models\AdmSetting;
use app\models\User;
use app\models\UserLogs;
use app\models\RestoreForm;

class GreenController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			]
		];
	}

	public $layout = "green_main";

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionPlan()
	{
		return $this->render('plan');
	}

	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}
		return $this->render('contact', [
			'model' => $model,
		]);
	}

	public function actionRegister($referal = '')
	{
		if (!Yii::$app->user->isGuest) {
			return $this->redirect(URL::to(['/user/index']));
		}
		$get_country = CountrySelect::find()
			->select(['name', 'id'])
			->indexBy('id')->column();

		$get_region = RegionSelect::find()
			->select(['name', 'id'])
			->where(['country_id' => 0])
			->indexBy('id')->column();

		$get_city = CitySelect::find()
			->select(['name', 'id'])
			->where(['region_id' => 0])
			->indexBy('id')->column();

		if (Yii::$app->request->isAjax) {
			if (Yii::$app->request->post('country', -1) != -1) {
				$country = Yii::$app->request->post();

				$get_region = RegionSelect::find()
					->select(['name', 'id'])
					->where(['country_id' => $country])
					->all();

				foreach ($get_region as $post) {

					echo "<option value='" . $post->id . "'>" . $post->name . "</option>";
				}
			}
			if (Yii::$app->request->post('region', -1) != -1) {
				$region = Yii::$app->request->post();

				$get_city = CitySelect::find()
					->select(['name', 'id'])
					->where(['region_id' => $region])
					->orderBy('id DESC')->all();

				foreach ($get_city as $post) {

					echo "<option value='" . $post->id . "'>" . $post->name . "</option>";
				}
			}
			if (Yii::$app->request->post('email', -1) != -1) {
				$email = Yii::$app->request->post();
				$key = rand(11111, 99999);
				Yii::$app->mailer->compose()
					->setFrom('noreply@lcgreenlife.com')
					->setTo($email['email'])
					->setSubject('Регистрация на GreenLife')
					->setTextBody('Код подтверждения Email')
					->setHtmlBody('<b>Код подтверждения Email:</b> ' . $key)
					->send();
				echo $key;
			}
			exit;
		}
		$form = ActiveForm::begin(['id' => 'register-form']);
		$model = new RegisterForm();
		if ($referal) {
			$referal = str_replace(' ', '', $referal);
			$ref = str_replace("AA", "", $referal);
			$referal = mb_strtolower($ref);
			$referal = 'AA' . $referal;
			$model->sponsor = $referal;
			$model->ref = $referal;
		}
		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$referal = str_replace(' ', '', $model->sponsor);
				$ref = str_replace("AA", "", $referal);
				$referal = mb_strtolower($ref);
				$referal = 'AA' . $referal;
				$model->sponsor = $referal;
				$model->ref = $referal;

				$pass = Yii::$app->request->post("RegisterForm");
				$model->pass = password_hash($pass['pass'], PASSWORD_DEFAULT);
				$model->save(false);

				$model->username = 'AA' . $this->uniqidReal(4) . $model->id;
				$model->dateReg = date("Y-m-d H:i:s");
				$model->save(false);

				Yii::$app->mailer->compose()
					->setFrom('noreply@lcgreenlife.com')
					->setTo($model->email)
					->setSubject('Успешная Регистрация на GreenLife')
					->setTextBody('Регистрация GreenLife')
					->setHtmlBody('<b>Ваш никнейм:</b> ' . $model->username)
					->send();

				Yii::$app->session->setFlash('message', "Вы успешно зарегистрировались!");
				$login = new LoginForm();
				$login->username = $model->username;
				$login->pass = $pass['pass'];
				if ($login->login()) {
					return $this->redirect(URL::to(['/user/index']));
				}
				return $this->refresh();
			} else Yii::$app->session->setFlash('message', "Ошибка! некоторые данные неверны!");
		}
		return $this->render('register', [
			'model' => $model,
			'get_country' => $get_country,
			'get_region' => $get_region,
			'get_city' => $get_city,
			'form' => $form
		]);
	}

	public function actionRestore()
	{
		if (Yii::$app->request->isAjax) {
			if (Yii::$app->request->post('email') == 'send') {
				$user = Yii::$app->request->post('user');
				$acc = User::findOne(['username' => $user]);

				if (!$acc) {
					return 'Пользователь с таким логином не найден!';
				}

				$key = rand(11111, 99999);
				$session = Yii::$app->session;

				if (!$session->isActive) {
					$session->open();
				}

				$session->set('key_restore', $key);
				$session->close();

				Yii::$app->mailer->compose()
					->setFrom('noreply@lcgreenlife.com')
					->setTo($acc->email)
					->setSubject('Восстановление аккаунта')
					->setTextBody('Код подтверждения Email')
					->setHtmlBody('<b>Код подтверждения Email:</b> ' . $key)
					->send();
				echo 'ok';
				exit();
			}

			return 0;
		}

		$model = new RestoreForm();
		if ($model->load(Yii::$app->request->post())) {
			$session = Yii::$app->session;
			if (!$session->isActive) $session->open();
			$key = $session->get('key_restore');
			$session->close();
			$model->emailCodeCheck = $key;
			if ($model->validate()) {
				$acc = User::findOne(['username' => $model->username]);
				if (is_null($acc)) return;
				$password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
				$new_pass = substr(str_shuffle($password_string), 0, 12);
				$acc->pass = password_hash($new_pass, PASSWORD_DEFAULT);
				$acc->save();
				Yii::$app->mailer->compose()
					->setFrom('noreply@lcgreenlife.com')
					->setTo($acc->email)
					->setSubject('Восстановление аккаунта')
					->setTextBody('Новый пароль аккаунта')
					->setHtmlBody('<b>Ваш новый пароль от аккаунта:</b> ' . $new_pass)
					->send();
				$this->refresh();
			}
		}
		return $this->render('restore', [
			'model' => $model]);
	}

	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->redirect(URL::to(['/user/index']));
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			$this->refresh();
			$logs = new UserLogs();
			$logs->type = 1;
			$logs->user_id = Yii::$app->user->identity->id;
			$logs->date = date("Y-m-d H:i:s");
			$logs->object = 'Пользователь';
			$logs->action = 'Вход в личный кабинет';
			$ip = Yii::$app->getRequest()->getUserIP();
			$ip = explode('.', $ip);
			$logs->detail = 'Login IP:' . (isset($ip[0]) ? $ip[0] : '::1') . '.' . (isset($ip[1]) ? $ip[1] : '::1') . '.*.*';
			$logs->save();
			return $this->redirect(URL::to(['/user/index']));
		}
		Yii::$app->session->setFlash('messageLogin', "Ошибка! данные неверны!");
		return $this->render('/green/index');
	}

	public function actionLogout()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(URL::to(['/green/index']));
		}
		$user_id = Yii::$app->user->identity->id;
		$logs = new UserLogs();
		$logs->type = 1;
		$logs->user_id = $user_id;
		$logs->date = date("Y-m-d H:i:s");
		$logs->object = 'Пользователь';
		$logs->action = 'Выход с личного кабинета';
		$ip = Yii::$app->getRequest()->getUserIP();
		$ip = explode('.', $ip);
		$logs->detail = 'Logout IP:' . (isset($ip[0]) ? $ip[0] : '') . '.' . (isset($ip[1]) ? $ip[1] : '') . '.*.*';
		$logs->save();

		Yii::$app->user->logout();
		return $this->redirect(['/green/index']);
	}

	function uniqidReal($lenght = 13)
	{
		// uniqid gives 13 chars, but you could adjust it to your needs.
		if (function_exists("random_bytes")) {
			$bytes = random_bytes(ceil($lenght / 2));
		} elseif (function_exists("openssl_random_pseudo_bytes")) {
			$bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
		} else {
			throw new Exception("no cryptographically secure random function available");
		}
		return substr(bin2hex($bytes), 0, $lenght);
	}

	public function actionPerfect()
	{
		if (!Yii::$app->request->post()) exit();
		$setting = AdmSetting::findOne(['id' => 1]);

		$secret = strtoupper(md5($setting->perfect_key));
		$hash = Yii::$app->request->post('PAYMENT_ID') . ':' .
			Yii::$app->request->post('PAYEE_ACCOUNT') . ':' .
			Yii::$app->request->post('PAYMENT_AMOUNT') . ':' .
			Yii::$app->request->post('PAYMENT_UNITS') . ':' .
			Yii::$app->request->post('PAYMENT_BATCH_NUM') . ':' .
			Yii::$app->request->post('PAYER_ACCOUNT') . ':' .
			$secret . ':' .
			Yii::$app->request->post('TIMESTAMPGMT');
		$hash = strtoupper(md5($hash));
		if ($hash != Yii::$app->request->post('V2_HASH')) exit('error');

		$user = User::findOne(['username' => Yii::$app->request->post('PAYMENT_ID')]);
		$money = Yii::$app->request->post('PAYMENT_AMOUNT') * $setting->kurs;
		$user->lc = $user->lc + $money;
		$user->save();

		//логирование
		$logs = new UserLogs();
		$logs->type = 0;
		$logs->user_id = $user->id;
		$logs->date = date("Y-m-d H:i:s");
		$logs->object = 'Счёт';
		$logs->action = 'Пополнение счёта';
		$logs->sum = $money;
		$logs->save();

		exit();
	}
}