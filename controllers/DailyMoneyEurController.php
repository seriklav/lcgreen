<?php

namespace app\controllers;

use Yii;
use app\models\DailyMoneyEur;
use app\models\search\DailyMoneySearch;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DailyMoneyEurController implements the CRUD actions for DailyMoney model.
 */
class DailyMoneyEurController extends Controller
{
    public $layout = "admin";
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'denyCallback'  =>  function($rule, $action)
                {
                    if($action->actionMethod != "actionLogin") return $this->redirect(URL::to(['green/index']));
                },
                'rules' =>  [
                    [
                        'allow' =>  true,
                        'matchCallback' =>  function($rule, $action)
                        {
                            $session = Yii::$app->session;
                            if(!$session->isActive) $session->open();
                            $login = $session->get('adm_login');
                            $session->close();

                            if($action->actionMethod == "actionLogin" && $login) return $this->redirect(URL::to(['admin/index']));
                            if($action->actionMethod == "actionLogin") return 1;

                            return $login;
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all DailyMoneyEur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DailyMoneySearch();
        $searchModel->type = 'eur';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DailyMoneyEur model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DailyMoneyEur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DailyMoneyEur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DailyMoneyEur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

	/**
	 * Deletes an existing DailyMoneyEur model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param $id
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DailyMoney model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DailyMoneyEur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DailyMoneyEur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
