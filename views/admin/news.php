<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Новости';
?>
<div class="row">
    <div class="col-sm-12">
        <h2>Новости</h2>
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\UserNews::find() ->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 50
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                ['label' => 'Заголовок', 'attribute' => 'title'],
                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{newsedit}',
                    'buttons' => [
                        'newsedit' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                                'title' => 'Редактировать',
                                'style' => 'margin: 0 20px; 0 0',
                                'data-pjax' => '0',
                            ]);
                        },
                    ]
                ],
            ]
        ]);
        ?>
        <h2>Добавить новость</h2>
        <?php $form = ActiveForm::begin(['id' => 'news']); ?>
        <div class="form-group">
            <label for="comment">Заголовок:</label>
            <?= $form->field($model, 'title')->textInput()->label(false) ?>
        </div>
        <div class="form-group">
            <label for="comment">Текст:</label>
            <?= $form->field($model, 'desc_text')->textarea(['rows' => '6'])->label(false) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-lg" style="display: block; margin: auto;">Добавить</button>
        <?php ActiveForm::end(); ?>
</div>
</div>
