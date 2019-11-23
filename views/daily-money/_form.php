<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DailyMoney */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="daily-money-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'place')->textInput() ?>

    <?= $form->field($model, 'give')->textInput() ?>

    <?= $form->field($model, 'clone_give')->textInput() ?>

    <?= $form->field($model, 'freeze')->textInput() ?>

    <?= $form->field($model, 'range')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'bonus')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'renvest')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\models\DailyMoney::getStatus()) ?>

    <?= $form->field($model, 'category')->dropDownList(\app\models\DailyMoney::getCategory()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
