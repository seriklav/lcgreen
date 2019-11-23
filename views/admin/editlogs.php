<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
$this->title = 'Редактирование логов';
?>
<div class="row">
    <div class="col-lg-5">
        <h3><?= '#'.$model->id;?></h3>
        <?php $form = ActiveForm::begin(['id' => 'update-logs']); ?>
        <?= $form->field($model, 'date')
            ->textInput(['value' => $model->date]) ?>
        <?= $form->field($model, 'sum')
            ->textInput(['value' => $model->sum]) ?>
        <?= $form->field($model, 'action')
            ->textInput(['value' => $model->action]) ?>
        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
