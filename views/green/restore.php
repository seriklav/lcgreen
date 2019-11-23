<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = 'Востановление пароля';
?>
<div class="content restor">
    <div class="container">
            <div class="alert alert-danger alert-dismissible hide" id="alert">
            </div>
        <h2>Востановление пароля</h2>
        <?php $form = ActiveForm::begin(['id' => 'restore-form']); ?>
        <div class="row">
            <p>Ваш логин <span class="star">*</span></p>
            <?= $form->field($model, 'username')
                ->label(false) ?>
        </div>
        <div class="row">
            <p>Код подтверждения <span class="star">*</span></p>
        </div>
        <div class="row">

            <?= $form->field($model, 'verifyCode')
                ->label(false)
                ->widget(Captcha::className(), [
                    'template' => '<div class="row mini"><div class="captcha">{image}</div>{input}</div>'
                ]) ?>
        </div>
        <div class="row">
            <p>Email код<span class="star">*</span></p>
            <?= $form->field($model, 'emailCode')
                ->label(false) ?>
        </div>
        <div class="row">
            <input type="button" class="button" value="Получить код" id="send_code">
        </div>
        <div class="row center">
            <span class="star">*</span> <span>- поля, обязательные для заполнения</span>
        </div>
        <div class="row center">
            <input type="submit" class="button big" value="Отправить">
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$url = URL::to(['/green/restore']);
$js = <<<JS
$('#send_code').on('click', function(event ) {
    event.preventDefault();
      $.ajax({
          url: "$url",
          data: {email: "send", user: $('#restoreform-username').val()},
          type: 'POST',
          success :function(code) {
            if(code == 'ok'){ 
                $('#alert').addClass('hide').html('');
                alert('На ваш Email был выслан код подтверждения!');}
            else {
                $('#alert').removeClass('hide').html(code);
            }
            },
          }
      );
});
JS;
$this->registerJs($js);
?>
