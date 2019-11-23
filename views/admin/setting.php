<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Настройки';
?>
<div class="row">
    <div class="col-lg-5">
        <h2>Настройки</h2>
        <?php $form = ActiveForm::begin(['id' => 'update-setting']); ?>
        <p>Курс LC к USD</p>
        <?= $form->field($model, 'kurs')
            ->textInput(['value' => $model->kurs]) ->label(false) ?>
        <p>Курс LC к EUR</p>
        <?= $form->field($model, 'kurs_eur')
            ->textInput(['value' => $model->kurs_eur]) ->label(false) ?>
        <h2>Perfect Money</h2>
        <p>Номер кошелька USD</p>
        <?= $form->field($model, 'perfect')
            ->textInput(['value' => $model->perfect]) ->label(false) ?>
        <p>Номер кошелька EUR</p>
        <?= $form->field($model, 'perfect_eur')
            ->textInput(['value' => $model->perfect_eur]) ->label(false) ?>
        <p>Альтернативная кодовая фраза</p>
        <?= $form->field($model, 'perfect_key')
            ->textInput(['value' => $model->perfect_key]) ->label(false) ?>
        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary', 'name' => 'update-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
