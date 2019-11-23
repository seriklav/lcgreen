<?php

namespace app\controllers;

use app\commands\MyClass;
use app\models\AdmSetting;
use app\models\DialogFeed;
use app\models\FeedBackMod;
use app\models\search\CloneSearch;
use app\models\UserClone;
use app\models\UserNews;
use app\models\UserWithdraw;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\web\Controller;
use app\models\User;
use app\models\UserLogs;
use app\models\Admin;
use app\models\AdminLogin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public $layout = "admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    if ($action->actionMethod != "actionLogin") return $this->redirect(URL::to(['green/index']));
                },
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $session = Yii::$app->session;
                            if (!$session->isActive) $session->open();
                            $login = $session->get('adm_login');
                            $session->close();

                            if ($action->actionMethod == "actionLogin" && $login) return $this->redirect(URL::to(['admin/index']));
                            if ($action->actionMethod == "actionLogin") return 1;

                            return $login;
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        $session->set('adm_login', 0);
        $session->close();

        return $this->redirect(URL::to(['green/index']));
    }

    public function actionLogin()
    {
        $admin = Admin::findOne(['id' => 1]);
        $model = new AdminLogin();

        if (MyClass::myIp()) {
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            $session->set('adm_login', 1);
            $session->close();
            return $this->redirect(URL::to(['admin/index']));
        }


        if ($model->load(Yii::$app->request->post())) {
            if ($model->username == $admin->user && password_verify($model->password, $admin->pass)) {
                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();
                $session->set('adm_login', 1);
                $session->close();
                return $this->redirect(URL::to(['admin/index']));
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionChangeadmin()
    {
        $model = new Admin();
        if ($model->load(Yii::$app->request->post())) {
            $admin = Admin::findOne(['id' => 1]);
            $admin->pass = password_hash($model->pass, PASSWORD_DEFAULT);
            $admin->save();
        }
        return $this->render('admin', ['model' => $model]);
    }

    public function actionIndex()
    {
        /*$query = User::find();

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 1]);
        $pages->pageSizeParam = false;
        $users = $query->offset($pages->offset)
            ->select(['id', 'username', 'name', 'surname', 'lc'])
            ->limit($pages->limit)
            ->all();*/

        return $this->render('index'/*, [
            'users' => $users,
            'pages' => $pages
        ]*/);
    }

    public function actionClones()
    {
        if (!MyClass::ips()) {
            throw new NotFoundHttpException();
        }


        $searchModel = new \app\models\search\CloneSearch();
        $dataProviderClones = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('clones', compact('searchModel', 'dataProviderClones'));
    }

    public function actionCloneSteps($cloneId)
    {
        if (!MyClass::ips()) {
            throw new NotFoundHttpException();
        }
        $model = UserClone::findOne(['id' => $cloneId]);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->getSteps()->all(),

        ]);

        return $this->render('clones-steps', compact('dataProvider'));
    }

    public function actionUpdate($id)
    {
        $model = User::findIdentity($id);
        if (Yii::$app->request->post()) $model = User::findIdentity($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->parol) {
                $model->pass = password_hash($model->parol, PASSWORD_DEFAULT);
            }
            $model->save();
            $this->refresh();
        }
        $logs = new UserLogs();
        if ($logs->load(Yii::$app->request->post())) {
            $logs->type = 0;
            $logs->user_id = $model->id;
            $logs->date = date("Y-m-d H:i:s");
            $logs->object = 'Счёт';
            $logs->save();
            $this->refresh();
        }

        $searchModel = new \app\models\search\CloneSearch();
        $dataProviderClones = $searchModel->search(Yii::$app->request->queryParams, $model);


        return $this->render('update', compact('model', 'logs', 'searchModel', 'dataProviderClones'));
    }

    public function actionEditlogs($id)
    {
        $model = UserLogs::findOne(['id' => $id]);
        if (is_null($model)) return;
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
        }
        return $this->render('editlogs', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = UserLogs::findOne(['id' => $id]);
        if (is_null($model)) return;
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteClone()
    {
        if (Yii::$app->request->post()) {
            if ($id = Yii::$app->request->post('id')) {
                $model = User::findOne(['id' => $id]);
                $clone = $model->getClones()->andWhere(['id' => Yii::$app->request->post('clone')])->one();
                /** @var  UserClone $clone */
                if ($clone) {
                    $clone->unlinkAll('steps', true);
                    $clone->delete();
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUpdatenote()
    {
        if (Yii::$app->request->post()) {
            $model = UserWithdraw::findOne(Yii::$app->request->post('id'));
            if (is_null($model)) return $this->redirect(URL::to(['admin/withdraw']));
            $model->note = Yii::$app->request->post('note');
            $model->save();
        }
        return $this->redirect(URL::to(['admin/withdraw']));
    }

    public function actionComplete($id)
    {
        $model = UserWithdraw::findOne(['id' => $id]);
        if (is_null($model)) return;
        $model->status = 1;
        $model->date = date("Y-m-d H:i:s");
        $model->save();
        return $this->redirect(URL::to(['admin/withdraw']));
    }

    public function actionNewdialog()
    {
        $support = new FeedBackMod();
        if ($support->load(Yii::$app->request->post())) {
            if ($support->validate()) {
                $support->new = 1;
                $support->send_date = date("Y-m-d H:i:s");
                $support->update_date = date("Y-m-d H:i:s");
                $support->last_send = -1;
                $support->who = -1;
                $support->save();

                $this->refresh();
            }
        }
        return $this->render('newdialog', [
            'model' => $support
        ]);
    }

    public function actionDialog($id)
    {
        $support = FeedBackMod::findOne(['id' => $id]);
        $dialog = DialogFeed::find()
            ->where(['support_id' => $id])
            ->all();
        $support->seen = 1;
        $support->save();
        $model = new DialogFeed();
        if ($model->load(Yii::$app->request->post())) {
            $model->support_id = $id;
            $model->date_ticket = date("Y-m-d H:i:s");
            $model->who = -1;
            $model->save();

            $support->new = 1;
            $support->update_date = date("Y-m-d H:i:s");
            $support->last_send = -1;
            $support->save();

            $this->refresh();
        }
        return $this->render('dialog', [
            'support' => $support,
            'model' => $model,
            'dialog' => $dialog,
        ]);
    }

    public function actionReinvest()
    {
        if ($id = Yii::$app->request->post('id')) {
            if ($model = UserClone::findOne(['id' => $id])) {
                if ($model->status_renvest) {
                    $model->status_renvest = 0;
                } else {
                    $model->status_renvest = 1;
                }
                $model->update();
            }
        }
        $searchModel = new CloneSearch();
        $searchModel->renvestAdmin = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('reinvest', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionWithdraw()
    {
        return $this->render('withdraw');
    }

    public function actionSupport()
    {
        return $this->render('support');
    }

    public function actionNews()
    {
        $model = new UserNews();
        if ($model->load(Yii::$app->request->post())) {
            $model->date = date("Y-m-d H:i:s");
            $model->save();
            $this->refresh();
        }
        return $this->render('news', [
            'model' => $model
        ]);
    }

    public function actionNewsedit($id)
    {
        $news = UserNews::findOne(['id' => $id]);
        if ($news->load(Yii::$app->request->post())) {
            $news->save();
            $this->refresh();
        }
        return $this->render('news_edit', [
            'news' => $news
        ]);
    }

    public function actionSetting()
    {
        $model = AdmSetting::findOne(['id' => 1]);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            $this->refresh();
        }
        return $this->render('setting', [
            'model' => $model
        ]);
    }
}