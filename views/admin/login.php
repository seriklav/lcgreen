<?php
$this->title = 'Авторизация';
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;?>
    <div class="col-lg-4 col-sm-12">
<?php $form = ActiveForm::begin(['id' => 'admin-login']); ?>
        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    </div>
<?php ActiveForm::end(); ?>