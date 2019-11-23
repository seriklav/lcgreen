<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\GreenAsset;

use yii\bootstrap\ActiveForm;
use app\models\LoginForm;
use app\models\UserLink;
use app\models\User;
GreenAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon">
</head>
<body>
<?php $this->beginBody() ?>
<style>
    .accounts{z-index: 9999;}
    .user-logo img{
        width: 80px;
        height: 80px;
    }
    .label{color: #0f0f0f !important;}
    .accounts .account a.serial-numb{
        float: left;
        margin-right: 5px;
        border-bottom: none;
        padding: 7px 5px 7px 0px;
    }
    .accounts{
        max-width: 100%;
    }
    .accounts .account{
        padding: 20px 10px 0 10px;
    }
    .accounts > .account{
        display: block !important;
    }
</style>
<div class="main-bg bg">
    <div class="cont">

        <header>
            <div class="container <?php if (!Yii::$app->user->isGuest) echo 'lk'; ?>">
                <div class="logo">
                    <a href="<?php echo URL::to(['/green/index']); ?>"> <?php echo Html::img('@web/img/logo.png') ?></a>
                </div>
                <div class="links-block">
                    <nav>
                        <span class="links <?php if(\Yii::$app->controller->action->id == 'index') echo('active'); ?>">
                            <?php echo HTML::a('Главная', URL::to(['/green/index'])) ?>
                        </span>
                        <span class="links <?php if(\Yii::$app->controller->action->id == 'plan') echo('active'); ?>">
                            <?php echo HTML::a('Маркетинговый план', URL::to(['/green/plan'])) ?>
                        </span>
                    </nav>
                </div>
                <?php if (Yii::$app->user->isGuest):?>
                <div class="links-block mobile">
                    <span class="links <?php if(\Yii::$app->controller->action->id == 'contact') echo('active'); ?>">
                         <?php echo HTML::a('Контакты', URL::to(['/green/contact'])) ?>
                    </span>
                    <span class="links <?php if(\Yii::$app->controller->action->id == 'register') echo('active'); ?>">
                         <?php echo HTML::a('Регистрация', URL::to(['/green/register'])) ?>
                    </span>
                    <div class="links"><span class="popup">Вход</span></div>
                </div>
                <?php endif;?>
               <?php if (!Yii::$app->user->isGuest):?>
                    <div class="user-menu">
                        <div class="user">
                            <div class="user-logo">
                                <?php if(!Yii::$app->user->identity->avatar) echo Html::img('@web/img/user.png');
                                else echo Html::img('@web/'.Yii::$app->user->identity->avatar); ?>
                                <div class="ident arrowed">
                                    <div class="name"><?php echo Yii::$app->user->identity->name; echo " ".Yii::$app->user->identity->surname;?></div>
                                    <div class="serial-numb"><?php echo Yii::$app->user->identity->username;?></div>
                                </div>
                            </div>
                        </div>
                        <div class="accounts">
                            <div class="title">Мои аккаунты</div>
                            <?php
                            $all_acc = UserLink::find() ->where(['link_id' => Yii::$app->user->identity->id]) ->all();
                            $all_link = UserLink::findOne(['user_id' => Yii::$app->user->identity->id]);
                            if(!$all_acc)
                            {
                                
                                if(!is_null($all_link))
                                {
                                    $all_acc = UserLink::find() ->where(['link_id' => $all_link->link_id]) ->all();
                                }
                            }
                            if(!$all_acc)
                            {
                            ?>
                                <a class="serial-numb" href="<?php echo URL::to(['/user/index']); ?>">
                                    <div class="account">
                                        <div class="serial-numb">
                                            <?php echo Yii::$app->user->identity->username; ?>
                                        </div>
                                        <div class="star"></div>
                                        <div class="status">online</div>
                                    </div>
                                </a>
                            <?php
                            }
                            else{?>
                                <?php  if(is_null($all_link)) $user = User::findOne(['id' => Yii::$app->user->identity->id]);
                               else $user = User::findOne(['id' => $all_link->link_id]); ?>
                                <a class="serial-numb" href="<?php if( Yii::$app->user->identity->id == $user->id) echo '#'; else echo URL::to(['/user/changeuser?user='.$user->username]); ?>">
                                    <div class="account">
                                        <div class="serial-numb">
                                            <?php echo $user->username; ?>
                                        </div>
                                        <div class="star"></div>
                                        <?php if($user->id ==  Yii::$app->user->identity->id): ?> <div class="status">online</div> <?php endif; ?>
                                    </div>
                                </a>
                                <div class="account">
                                 <?php
                                    foreach ($all_acc as $acc):
                                    $user = app\models\User::findOne(['id' => $acc->user_id]); ?>
                                        <a class="serial-numb" href="<?php if( Yii::$app->user->identity->id == $acc->user_id) echo '#'; else echo URL::to(['/user/changeuser?user='.$user->username]); ?>">
                                            <?php echo $user->username; ?> <?php if( Yii::$app->user->identity->id == $acc->user_id): ?> (<span class="status">online</span>) <?php endif; ?>
                                        </a>
                                     <?php endforeach; }?>
                                </div>
                            <span class="sign-out"><?php
                                echo Html::beginForm(URL::to(['/green/logout']), 'post')
                            . Html::submitButton('Выйти')
                         . Html::endForm() ?></span>
                        </div>
                    </div>
                <?php endif;?>
                <?php if (Yii::$app->user->isGuest):?>
                <div class="auth-popup">
                    <div class="title">Вход в личный кабинет</div>
                    <div class="popup-cont">
                        <div class="r-text"><?php echo HTML::a('Забыли пароль', URL::to(['/green/restore'])) ?></div>
                        <?php $form = ActiveForm::begin(['id' => 'login-form','action' => URL::to(['/green/login']) ]); ?>
                            <?php $model = new LoginForm(); ?>
                            <?= $form->field($model, 'username')
                            ->textInput(['placeholder' => "Логин"])
                            ->label(false) ?>
                        <?= $form->field($model, 'pass')
                            ->passwordInput(['placeholder' => "Пароль"])
                            ->label(false) ?>
                        <?= Html::submitButton('Вход', ['class' => 'button']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <?php endif;?>
                <div class="mobile-btn"></div>
            </div>
        </header>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->controller->id == 'user'):?>
        <div class="footnote">
            <div class="container">
                <div class="l-side">
                    <h2>Главный аккаунт</h2>
                    <p>Выберите один главный аккаунт, который станет вашим "лицом" в компании. Привяжите к главному аккаунту все свои технические аккаунты и управляйте ими из кабинета главного аккаунта.</p>
                </div>
                <div class="r-side">
                    <a href="#" class="button big start">Начать</a>
                </div>
            </div>
            <div class="steps-popup">
                <div class="close"></div>
                <div class="step-cont active">
                    <div class="title">
                        Укажите технический аккаунт который будет привязан к текущему
                    </div>
                    <?php

                    $link_model = new UserLink();
                    $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'link-account',
                        'action' => URL::to(['/user/savelink']),
                        'enableAjaxValidation' => true,
                        'validationUrl' => URL::to(['/user/link']),
                    ]); ?>
                    <div class="string vertical-center">
                        <div class="input-block">
                            <?= $form->field($link_model, 'account')->textInput(); ?>
                        </div>
                    </div>
                    <div class="string vertical-center">
                        <div class="input-block">
                            <?= $form->field($link_model, 'emailCode')->textInput(); ?>
                        </div>
                        <div class="button-block">
                            <input id="send_link" type="button" class="button" value="Получить код">
                        </div>
                    </div>
                    <div class="string">
                        <div class="info">
                            <div class="heading">Как привязать LC Greenlife аккаунты?</div>
                            <div class="text">Выберите основной аккаунт для программы LC Greenlife и привяжите к нему все технические LC Greenlife аккаунты</div>
                        </div>
                        <input type="submit" class="button big self-end" value="Привязать">
                    </div>
                    <?php $form->end(); ?>
                </div>
<?php
$url = URL::to(['/user/linkcode']);
$js = <<<JS
    $('#link-account').on('beforeSubmit', function () {
    var yiiform = $(this);
    // отправляем данные на сервер
    $.ajax({
    type: yiiform.attr('method'),
    url: yiiform.attr('action'),
    data: yiiform.serializeArray()
    }
    )
    .done(function(data) {
    if(data.success) {
        $('#link-account').html('<p>Аккаунт успешно привязан!</p>');
    } 
    })
    .fail(function () {
    alert('Ошибка!');
    })
    return false; 
    })
    
     $('#send_link').on('click', function(event ) {
         if( $('.field-userlink-account').hasClass('has-error')) return false; 
        event.preventDefault();
        $.ajax({
        url: "$url",
        data: {email: "send"},
        type: 'POST',
        success :function() {
            alert('На Email аккаунта который вы привязываете был выслан код подтверждения!');
        },
        }
        );
    });

JS;
$this->registerJs($js);?>
                <div class="step-cont">
                    <div class="sides">
                        <div class="l-side">
                            <div class="title">
                                <span class="bold">Шаг 4 -</span> Aккаунты привязаны
                            </div>
                            <div class="string">
                                <div class="heading-text">Главный аккаунт основной программы</div>
                            </div>
                            <div class="string vertical-center">
                                <div class="user">
                                    <div class="name">Маша Мишкина</div>
                                    <div class="ident">aa42726</div>
                                </div>
                            </div>
                        </div>
                        <div class="r-side">
                            <div class="desc-block">
                                <div class="text">Технические</div>
                                <div class="text">AA26004</div>
                            </div>
                        </div>
                    </div>
                    <div class="string buttons centered vertical-center">
                        <input type="button" class="button close" value="Завершить">
                        <span>или</span>
                        <input type="button" class="button" value="Привязать аккаунты">
                        <span>Осталось ещё 99</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-section">
            <div class="tabs main active">
                <div class="heading">
                    <div class="hide-show">
                        <div class="hide active">
                            <?php echo Html::img('@web/img/arrow-left-white.svg') ?>
                            <div>Скрыть</div>
                        </div>
                        <div class="show">
                            <?php echo Html::img('@web/img/menu-white.svg') ?>
                            <div>Меню</div>
                        </div>
                    </div>
                    <div class="stats">
                        <h2><?php echo Yii::$app->user->identity->name; echo " ".Yii::$app->user->identity->surname;?></h2>
                        <h2>Баланс: <span><?php echo Yii::$app->user->identity->lc; ?> LC</span></h2>
                        <h2>Баланс(EUR): <span><?php echo Yii::$app->user->identity->eur; ?> EUR</span></h2>
                        <!-- <h2>Структура: <span>1</span></h2> -->
                    </div>
                </div>
                <style>
                    a.tab{
                        display: block;
                        text-decoration: none;
                    }
                    a.tab:hover{ color: #ffff; }
                </style>
                <div class="tab-block">
                    <a href="<?php echo URL::to(['/user/index'])?>" class="tab profile <?php if(\Yii::$app->controller->action->id == 'index') echo('active'); ?>">Профиль</a>
                    <a href="<?php echo URL::to(['/user/team'])?>" class="tab comand <?php if(\Yii::$app->controller->action->id == 'team') echo('active'); ?>">Моя команда</a>
                    <a href="<?php echo URL::to(['/user/finance'])?>" class="tab whalet <?php if(\Yii::$app->controller->action->id == 'finance') echo('active'); ?>">Финансовая информация</a>
                    <a href="<?php echo URL::to(['/user/market'])?>" class="tab market <?php if(\Yii::$app->controller->action->id == 'market') echo('active'); ?>">Маркет</a>
                    <a href="<?php echo URL::to(['/user/monitoring'])?>" class="tab monitoring <?php if(\Yii::$app->controller->action->id == 'monitoring') echo('active'); ?>">Мониторинг</a>
                    <a href="<?php echo URL::to(['/user/daily-money'])?>" class="tab daily-money <?php if(\Yii::$app->controller->action->id == 'daily-money') echo('active'); ?>">Ежедневные Деньги</a>
                    <a href="<?php echo URL::to(['/user/daily-money-eur'])?>" class="tab daily-money-eur <?php if(\Yii::$app->controller->action->id == 'daily-money-eur') echo('active'); ?>">Ежедневные Деньги(EUR)</a>
                    <a href="<?php echo URL::to(['/user/news'])?>" class="tab news <?php if(\Yii::$app->controller->action->id == 'news') echo('active'); ?>">Новости</a>
                    <?php $new_mess = \app\models\FeedBackMod::find() -> where(['send' => Yii::$app->user->identity->username,'last_send' => -1,'new' => 1]) ->orWhere(['who' => Yii::$app->user->identity->username,'last_send' => -1,'new' => 1]) -> count(); ?>
                    <a href="<?php echo URL::to(['/user/feedback'])?>" class="tab support <?php if(\Yii::$app->controller->action->id == 'feedback') echo('active'); ?>" >Техподдержка <?php if($new_mess): ?> <strong style="color: #d43f3a;">(<?php echo $new_mess; ?>)</strong> <?php endif; ?></a>
                    <?php
                    echo Html::beginForm(URL::to(['/green/logout']), 'post')
                        . Html::submitButton('Выйти',['class' => 'tab'])
                        . Html::endForm() ?>
                </div>
            </div>
            <div class="tab-content main">
            <?php endif;?>

        <?= Alert::widget() ?>
        <?= $content ?>

            <?php if (!Yii::$app->user->isGuest):?>
            <div class="to-top">
                <div class="arrow"></div>
                <span>Вверх</span>
            </div>
        </div>
        <?php endif;?>

    </div>
    <footer>
        <div class="container">
            <p><?= date('Y') ?> LС Greenlife Все права защищены</p>
        </div>
    </footer>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
