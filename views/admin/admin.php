<?php
use yii\bootstrap\ActiveForm;
$this->title = 'Админ';
?>
<div class="row">
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['id' => 'admin']); ?>
        <div class="form-group">
            <label for="comment">Новый пароль:</label>
            <?= $form->field($model, 'pass')->textInput()->label(false) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-lg" style="display: block; margin: auto;">Сменить</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>
