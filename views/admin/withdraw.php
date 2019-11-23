<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Заявки на вывод';
?>
<div class="row">
    <div class="col-sm-12">
        <h2>Заявки на вывод</h2>
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\UserWithdraw::find() ->where(['status' => 0]) ->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 30
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                ['label' => 'Пользовтаель',
                     'attribute' => 'user_id',
                    'value' => function ($data)
                    {
                        return \app\models\User::findOne(['id' => $data['user_id']])->username;
                    },
                ],
                ['label' => 'Способ вывода', 'attribute' => 'type'],
                ['label' => 'Кошелек', 'attribute' => 'wallet'],
                ['label' => 'Сумма', 'attribute' => 'sum'],
                [
                    'label' => 'Примечание',
                    'attribute' => 'note',
                    'value' => function($model){
                        return
                            Html::beginForm(URL::to(['/admin/updatenote']), 'post')
                            .
                            Html::textarea('note', $model->note).Html::textInput('id', $model->id,['type' => 'hidden']).
                            Html::submitButton('обновить',['class'=>'btn btn-primary','style'=>'display: block;']). Html::endForm();
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{complete}',
                    'buttons' => [
                        'complete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                                'title' => 'Complete',
                                'style' => 'margin: 0 20px; 0 0',
                                'data-pjax' => '0',
                            ]);
                        },
                        ]
                ],
            ]
        ]);
        ?>
        <h2>История выводов</h2>
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\UserWithdraw::find() ->where(['status' => 1]) ->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 30,
                'pageParam' => 'page2'
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                ['label' => 'Пользовтаель',
                    'attribute' => 'user_id',
                    'value' => function ($data)
                    {
                        return \app\models\User::findOne(['id' => $data['user_id']])->username;
                    },
                ],
                ['label' => 'Способ вывода', 'attribute' => 'type'],
                ['label' => 'Кошелек', 'attribute' => 'wallet'],
                ['label' => 'Сумма', 'attribute' => 'sum'],
                ['label' => 'Дата выплаты', 'attribute' => 'date'],
                [
                    'label' => 'Примечание',
                    'attribute' => 'note',
                    'value' => function($model){
                        return
                            Html::beginForm(URL::to(['/admin/updatenote']), 'post')
                            .
                            Html::textarea('note', $model->note).Html::textInput('id', $model->id,['type' => 'hidden']).
                            Html::submitButton('обновить',['class'=>'btn btn-primary','style'=>'display: block;']). Html::endForm();
                    },
                    'format' => 'raw'
                ]
            ]
        ]);
        ?>
    </div>
</div>
