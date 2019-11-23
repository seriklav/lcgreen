<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты';
?>
<div class="content contacts">
    <div class="container">
        <div class="heading">
            <h2>Контакты</h2>
            <div class="text">
                <div>Для связи с нами воспользуйтесь формой ниже.</div>
                <div>Наши менеджеры свяжутся с вами в самое ближайшее время.</div>
            </div>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

        <div class="row v2">
            <p>Ваше имя <span class="star">*</span></p>
            <?= $form->field($model, 'name')
                ->textInput(['autofocus' => true])
                ->label(false) ?>
        </div>
        <div class="row v2">
            <p>Ваш e-mail <span class="star">*</span></p>
            <?= $form->field($model, 'email')
                ->label(false) ?>
        </div>
        <div class="row v2">
            <p>Тема сообщения <span class="star">*</span></p>
            <?= $form->field($model, 'subject')
                ->label(false) ?>
        </div>
        <div class="row v2">
            <p>Текст сообщения <span class="star">*</span></p>
            <?= $form->field($model, 'body')
                ->textarea(['rows' => 6])
                ->label(false) ?>
        </div>
        <div class="heading row">
            <div>Код подтверждения <span class="star">*</span></div>
        </div>
        <div class="row v2">
        <?= $form->field($model, 'verifyCode')
            ->label(false)
            ->widget(Captcha::className(), [
            'template' => '<div class="row mini"><div class="captcha">{image}</div>{input}</div>',
        ]) ?>
        </div>
        <div class="row center">
            <span class="star">*</span> <span>- поля, обязательные для заполнения</span>
        </div>
        <div class="row center">
            <?= Html::submitButton('Отправить', ['class' => 'button big', 'name' => 'contact-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
