<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Greenlife';
?>
<?php if(Yii::$app->session->hasFlash('messageLogin')): ?>
    <div class="alert alert-danger alert-dismissible show" role="alert">
        <?php echo Yii::$app->session->getFlash('messageLogin'); ?>
    </div>
<?php endif; ?>
<div class="main-page-slider">
    <div class="container">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <h2 class="title">УНИКАЛЬНЫЙ И КРЕАТИВНЫЙ МАРКЕТИНГ</h2>
                    <div class="l-side">
                        <p class="text"> Наша Компания разработала уникальный Маркетинг, отличный от других компаний;<br>  В Маркетинге включены все выплаты в сеть;<br> Самый щедрый маркетинг план 2018-2020 года;<br> Индивидуальные Стратегии работы для каждого;<br>  Маркетинг "Трансформер" и многое другое что выделяет нашу компанию от других;</p>
                        <a href="<?php echo URL::to(['/green/register']); ?>" class="button">Регистрация</a>
                    </div>
                    <div class="r-side">
                        <?php echo Html::img('@web/img/planet.png') ?>
                    </div>
                </div>
                <div class="swiper-slide">
                    <h2 class="title">ПРОФЕССИОНАЛЬНАЯ КОМАНДА РАЗРАБОТЧИКОВ</h2>
                    <div class="l-side">
                        <p class="text">Уникальная Стратегия "Команды" является интелектуальной разработкой профессионалов Сетевого Бизнеса компании LC Greenlife;<br> Гибкая система понятная в использовании, Выгодно окупает Ваши вложенные средства и время;</p>
                        <a href="<?php echo URL::to(['/green/register']); ?>" class="button">Регистрация</a>
                    </div>
                    <div class="r-side">
                        <?php echo Html::img('@web/img/clock.png') ?>
                    </div>
                </div>
                <div class="swiper-slide">
                    <h2 class="title">ИНОВАЦИОННЫЕ РЕШЕНИЯ</h2>
                    <div class="l-side">
                        <p class="text">Адаптивная система движения структуры, подходит как для профессионалов сетевого бизнеса, так и для новичков.</p>
                        <a href="<?php echo URL::to(['/green/register']); ?>" class="button">Регистрация</a>
                    </div>
                    <div class="r-side">
                        <?php echo Html::img('@web/img/dollar.png') ?>
                    </div>
                </div>
                <div class="swiper-slide">
                    <h2 class="title">МАРКЕТ ТОВАРОВ И УСЛУГ</h2>
                    <div class="l-side">
                        <p class="text">Каждый партнёр нашей компании получит доступ в кабинет маркет и имеет возможность приобретать товары или пользоваться услугами по специальным ценам.</p>
                        <a href="<?php echo URL::to(['/green/register']); ?>" class="button">Регистрация</a>
                    </div>
                    <div class="r-side">
                        <?php echo Html::img('@web/img/apple.png') ?>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>

    </div>
</div>
