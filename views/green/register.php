<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Регистрация';
?>
<?php
$url = URL::to(['/green/register']);
$js = <<<JS
$('#registerform-country').on('change', function() {
  $.ajax({
      url: "$url",
      data: {country: $(this).find(":selected").val()},
      type: 'POST',
      success :function(regions) {
            $('#registerform-region').html(regions);
              $.ajax({
              url: "$url",
              data: {region: $('#registerform-region').find(":selected").val()},
              type: 'POST',
              success :function(city) {
                $('#registerform-city').html(city);
              },
              });
      },
      }
  );
}); 

$('#registerform-region').on('change', function() {
  $.ajax({
      url: "$url",
      data: {region: $(this).find(":selected").val()},
      type: 'POST',
      success :function(city) {
        $('#registerform-city').html(city);
      },
      }
  );
});
var keys = 0;
$('#send_code, #send_code_repeat').on('click', function(event ) {
    event.preventDefault();
    if( $('#registerform-email').val() && $('#registerform-email').val() == $('#registerform-emailrepeat').val())
        {
          $.ajax({
              url: "$url",
              data: {email: $('#registerform-email').val()},
              type: 'POST',
              success :function(key) {
                alert('На ваш Email был выслан код подтверждения!');
                keys = key;
                },
              }
          );
        }
        else alert('Email не введен или он не совпадает с повторным!');
});
$('#register-button').on('click', function(event ) {
    if( $('#registerform-emailcode').val() && $('#registerform-emailcode').val() == keys && keys != 0)
        {
            
            
        }
        else
        {
            event.preventDefault();
            alert('Неверный Email код!');
        }
});
JS;
$this->registerJs($js);
?>
<div class="content reg">
    <?php if(Yii::$app->session->hasFlash('message')): ?>
        <div class="alert alert-info alert-dismissible show" role="alert">
            <?php echo Yii::$app->session->getFlash('message'); ?>
        </div>
    <?php endif; ?>
                <div class="container">
                    <h2>Регистрация нового пользователя</h2>
                    <?php $form; ?>
                    <div class="row sb">
                        <div class="input-block">
                            <p>Фамилия <span class="star">*</span></p>
                            <?= $form->field($model, 'surname')
                                ->textInput(['autofocus' => true,'placeholder' => "Фамилия"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Имя <span class="star">*</span></p>
                            <?= $form->field($model, 'name')
                                ->textInput(['placeholder' => "Имя"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Отчество</p>
                            <?= $form->field($model, 'father')
                                ->textInput(['placeholder' => "Отчество"])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row sb">
                        <div class="input-block">
                            <p>Ваш спонсор <span class="star">*</span></p>
                            <?= $form->field($model, 'sponsor')
                                ->textInput(['placeholder' => "Ваш спонсор"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Пол <span class="star">*</span></p>
                            <?= $form->field($model, 'sex')
                                ->dropdownList(array('Мужской','Женский'),['prompt'=>'Пол'])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Дата рождения <span class="star">*</span></p>
                            <?= $form->field($model, 'dateBirth')
                                ->textInput(['placeholder' => "Дата рождения"])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row sb">
                        <div class="input-block">
                            <p>Страна <span class="star">*</span></p>
                            <?= $form->field($model, 'country')
                                ->dropdownList($get_country)
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Область <span class="star">*</span></p>
                            <span id="region">
                               <?= $form->field($model, 'region')
                                   ->dropdownList($get_region,['prompt'=>'Выберите область'])
                                   ->label(false) ?>
                            </span>
                        </div>
                        <div class="input-block">
                            <p>Город <span class="star">*</span></p>
                            <?= $form->field($model, 'city')
                                ->dropdownList($get_city,['prompt'=>'Выберите город'])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row sb">
                        <div class="input-block">
                            <p>E-mail <span class="star">*</span></p>
                            <?= $form->field($model, 'email')
                                ->textInput(['placeholder' => "E-mail"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Телефон</p>
                            <?= $form->field($model, 'phone')
                                ->textInput(['placeholder' => "Телефон"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Skype</p>
                            <?= $form->field($model, 'skype')
                                ->textInput(['placeholder' => "Skype"])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row sb">
                        <div class="input-block">
                            <p>Повторите e-mail <span class="star">*</span></p>
                            <?= $form->field($model, 'emailRepeat')
                                ->textInput(['placeholder' => "Повторите e-mail"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Пароль <span class="star">*</span></p>
                            <?= $form->field($model, 'pass')
                                ->passwordInput(['placeholder' => "Пароль"])
                                ->label(false) ?>
                        </div>
                        <div class="input-block">
                            <p>Повторите пароль <span class="star">*</span></p>
                            <?= $form->field($model, 'passRepeat')
                                ->passwordInput(['placeholder' => "Повторите пароль"])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row sb">
                        <div class="input-block">
                        </div>
                        <div class="input-block resend-block">
                            <p class="resend"><span class="text">Не получили код?</span><a href="#" id="send_code_repeat">Отправить код еще раз</a></p>
                            <input type="button" class="button" value="Получить код" id="send_code">
                        </div>
                        <div class="input-block">
                            <p>E-mail код подтверждения <span class="star">*</span></p>
                            <?= $form->field($model, 'EmailCode')
                                ->textInput(['placeholder' => "E-mail код подтверждения"])
                                ->label(false) ?>
                        </div>
                    </div>
                    <div class="row center rules">
                        <label> <?= $form->field($model, 'agree')
                                ->checkbox()
                                ->label(false) ?><span>Я согласен с <a href="#">условиями</a></span></label>
                    </div>
                    <div class="row center">
                        <?= Html::submitButton('Стать участником', ['class' => 'button big', 'name' => 'register-button', 'id' => 'register-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
<?php $this->registerJsFile('@web/js/jquery.mask.js',['depends' => 'yii\web\YiiAsset']); ?>
<?php
$js = <<<JS
    $('#registerform-datebirth').mask('00.00.0000');
JS;
$this->registerJs($js);
?>
