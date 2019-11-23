<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\User;
use app\models\UserLogs;
use app\models\AdmSetting;
use yii\helpers\Url;
use yii\web\Response;
use Yii;

class PerfectController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionSucces()
    {
        return $this->redirect(URL::to(['/green/index']));
    }
    public function actionPerfect()
    {
        if(!Yii::$app->request->post()) exit();
        $setting = AdmSetting::findOne(['id' => 1]);

        $secret = strtoupper( md5($setting->perfect_key) );
        $hash = Yii::$app->request->post('PAYMENT_ID').':'.
            Yii::$app->request->post('PAYEE_ACCOUNT').':'.
            Yii::$app->request->post('PAYMENT_AMOUNT').':'.
            Yii::$app->request->post('PAYMENT_UNITS').':'.
            Yii::$app->request->post('PAYMENT_BATCH_NUM').':'.
            Yii::$app->request->post('PAYER_ACCOUNT').':'.
            $secret.':'.
            Yii::$app->request->post('TIMESTAMPGMT');
        $hash = strtoupper( md5($hash) );
        if ( $hash != Yii::$app->request->post('V2_HASH') ) exit('error');

        $user = User::findOne(['username' => Yii::$app->request->post('PAYMENT_ID')]);

        $money = $this->convertPrice($user, $setting);

        //логирование
        $logs = new UserLogs();
        $logs->type = 0;
        $logs->user_id = $user->id;
        $logs->date = date("Y-m-d H:i:s");
        $logs->object = 'Счёт';
        $logs->action = 'Пополнение счёта (' . Yii::$app->request->post('PAYMENT_UNITS') . ')';
        $logs->sum = $money;
        $logs->save();
        
        exit();
    }

	/**
	 * @param User $user
	 * @param AdmSetting $setting
	 * @return float|int
	 */
    private function convertPrice($user, $setting) {
	    switch (Yii::$app->request->post('PAYMENT_UNITS')) {
		    case 'EUR':
		    	$kurs  = $setting->kurs_eur;
		    	break;
		    default:
			    $kurs  = $setting->kurs;
		    	break;
	    }

	    $money = Yii::$app->request->post('PAYMENT_AMOUNT') * $kurs;
	    $money = round($money, 0, PHP_ROUND_HALF_UP);

	    switch (Yii::$app->request->post('PAYMENT_UNITS')) {
		    case 'EUR':
			    $user->eur = $user->eur + $money;
			    break;
		    default:
			    $user->lc = $user->lc + $money;
			    break;
	    }

	    $user->save();

	    return $money;
    }
}