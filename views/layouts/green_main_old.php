<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\GreenAsset;

use yii\bootstrap\ActiveForm;
use app\models\LoginForm;

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
                        <span class="links"><a href="#">Бизнес школа</a></span>
                        <span class="links"><a href="#">Вебинары</a></span>
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
                                <?php echo Html::img('@web/img/user.png') ?>
                                <div class="ident arrowed">
                                    <div class="name"><?php echo Yii::$app->user->identity->name; echo " ".Yii::$app->user->identity->surname;?></div>
                                    <div class="serial-numb"><?php echo Yii::$app->user->identity->username;?></div>
                                </div>
                            </div>
                        </div>
                        <div class="accounts">
                            <div class="title">Мои аккаунты</div>
                            <a class="serial-numb" href="<?php echo URL::to(['/user/index']); ?>">
                                <div class="account">
                                    <div class="serial-numb">
                                        <?php echo Yii::$app->user->identity->username; ?>
                                    </div>
                                    <div class="star"></div>
                                    <div class="status">online</div>
                                </div>
                            </a>
<!--                            <div class="account">-->
<!--                                <div class="serial-numb">aa26004</div>-->
<!--                            </div>-->
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
                    <a href="#">Правила работы с главными и техническими аккаунтами</a>
                </div>
                <div class="r-side">
                    <a href="#" class="button big start">Начать</a>
                </div>
            </div>
            <div class="steps-popup">
                <div class="close"></div>
                <div class="steps">
                    <div class="step done">шаг 1</div>
                    <div class="arrow"></div>
                    <div class="step active">шаг 2</div>
                    <div class="arrow"></div>
                    <div class="step">шаг 3</div>
                    <div class="arrow"></div>
                    <div class="step">шаг 4</div>
                </div>
                <div class="step-cont active">
                    <div class="title">
                        <span class="bold">Шаг 2 -</span> Выберите главный аккаунт программы FREE LIFE
                    </div>
                    <div class="string">
                        <div class="input-block">
                            <div class="label">Введите логин</div>
                            <input type="text">
                        </div>
                        <input type="button" class="button" Value="Поиск">
                    </div>
                    <div class="string flex-end">
                        <input type="button" class="button big next-step" value="Выбрать позже">
                    </div>
                </div>
                <div class="step-cont">
                    <div class="title">
                        <span class="bold">Шаг 3 -</span> Укажите технические аккаунты
                    </div>
                    <div class="string vertical-center">
                        <div class="input-block">
                            <input type="text">
                        </div>
                        <input type="button" class="button" Value="Поиск">
                        <input type="button" class="button big self-end" Value="Выбрать главный">
                    </div>
                    <div class="string">
                        <div class="info">
                            <div class="heading">Как привязать FREE LIFE аккаунты?</div>
                            <div class="text">Выберите основной аккаунт для программы FREE LIFE и привяжите к нему все технические FREE LIFE аккаунты</div>
                        </div>
                        <input type="button" class="button big self-end next-step" value="Дальше">
                    </div>
                </div>
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
                        <h2>Баланс: <span>0 LC</span></h2>
                        <h2>Структура: <span>1</span></h2>
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
                    <div class="tab whalet">Финансовая информация</div>
                    <div class="tab market">Маркет</div>
                    <div class="tab monitoring">Мониторинг</div>
                    <div class="tab news">Новости</div>
                    <div class="tab support">Техподдержка</div>
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
            <p> <a href="//www.free-kassa.ru/"><img src="//www.free-kassa.ru/img/fk_btn/6.png"></a></p>
        </div>
    </footer>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
