<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
    $renvestCount = app\models\UserClone::find()->where(['status_renvest' => 1])->count();
    $renvestText = 'Ренвест';
    if($renvestCount) {
        $renvestText.= ' <strong style="color: #d43f3a;">('. $renvestCount .')</strong>';
    }
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon">
	<style>
		.nav > li > a {
			padding: 15px 5px;
		}
		.navbar-brand {
			font-size: 14px;
		}
	</style>
</head>

<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <div class="row">
            <?php $new_mess = \app\models\FeedBackMod::find() ->where(['<>','last_send', -1]) ->andWhere(['seen' => 0]) -> count(); ?>
            <?php if($new_mess): $new_text = 'Тех. поддержка'.' <strong style="color: #d43f3a;">('. $new_mess .')</strong>'; ?>
            <?php else: $new_text = 'Тех. поддержка'; endif;?>
            <?php $new_mess = \app\models\UserWithdraw::find() ->Where(['status' => 0]) -> count(); ?>
            <?php if($new_mess): $new_with = 'Заявки на вывод'.' <strong style="color: #d43f3a;">('. $new_mess .')</strong>'; ?>
            <?php else: $new_with = 'Заявки на вывод'; endif;?>
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->name.' админ панель',
                'brandUrl' => URL::to(['/admin/index']),
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            if(\Yii::$app->controller->action->id != 'login') :
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => [
                    ['label' => 'Пользователи', 'url' => ['/admin/index']],
                    ['label' => $new_with, 'url' => ['/admin/withdraw']],
                    ['label' => $new_text, 'url' => ['/admin/support']],
                    ['label' => $renvestText, 'url' => ['/admin/reinvest']],
                    ['label' => 'Новости', 'url' => ['/admin/news']],
                    ['label' => 'Ежедневные Деньги', 'url' => ['/daily-money']],
                    ['label' => 'Ежедневные Деньги(EUR)', 'url' => ['/daily-money-eur']],
                    ['label' => 'Настройки', 'url' => ['/admin/setting']],
                    ['label' => 'Выйти', 'url' => ['/admin/logout']],
                ],
            ]);
            endif;
            NavBar::end();
            ?>
        </div>

<?php echo $content; ?>
</div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>