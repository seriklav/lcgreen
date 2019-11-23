<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
$this->title = 'Редактирование';
?>
<div class="row">
    <div class="col-lg-5">
        <h3><?= $model->name.' '.$model->surname.' '.$model->father .'('.$model->username.')';?></h3>
        <?php $form = ActiveForm::begin(['id' => 'update-user']); ?>
        <?= $form->field($model, 'lc')
            ->textInput(['value' => $model->lc]) ?>
        <?= $form->field($model, 'eur')
            ->textInput(['value' => $model->eur]) ?>
        <?= $form->field($model, 'email')
            ->textInput(['value' => $model->email]) ?>
        <?= $form->field($model, 'parol')
            ->textInput() ?>
        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
        <div class="col-sm-12">
        <h2>Лог пользователя</h2>
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'insert-log']); ?>
            <p>Сумма</p>
            <?= $form->field($logs, 'sum')
                ->textInput(['value' => $logs->sum])->label(false) ?>
            <p>Детали</p>
            <?= $form->field($logs, 'action')
                ->textInput(['value' => $logs->action])->label(false) ?>
            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div style="clear: both;"></div>
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\UserLogs::find() ->where(['user_id' => $model->id,'type' => 0]) ->orderBy(['date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 15
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                ['label' => 'Дата', 'attribute' => 'date'],
                ['label' => 'Сумма', 'attribute' => 'sum'],
                ['label' => 'Детали операции', 'attribute' => 'action'],
                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{editlogs}{delete}',
                    'buttons' => [
                        'editlogs' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                                'title' => 'Edit',
                                'style' => 'margin: 0 20px; 0 0',
                                'data-pjax' => '0',
                            ]);
                        },
                    ]
                ],
            ]
        ]);
        ?>
    </div>
</div>
