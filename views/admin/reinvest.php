<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

Pjax::begin();
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'name',
        'renvest',
        'freeze',
        [
            'attribute' => 'date_renvest',
            'format' => 'dateTime',
            'value' => function($model) {
                return $model->date_renvest? $model->date_renvest : null;
            }
        ],
        [
            'attribute' => 'status_renvest',
            'format' => 'raw',
            'value' => function($model) {
                if($model->renvest) {
                    $html = Html::beginForm('', 'post');
                    $html.= Html::hiddenInput('id', $model->id);
                    if($model->status_renvest) {
                        $html.= Html::button('Не выполнено ',  ['type' => 'submit', 'class' => 'btn btn-danger']);
                    } else {
                        $html.= Html::button('Выполнено ',  ['type' => 'submit', 'class' => 'btn btn-success']);
                    }

                    $html.= Html::endForm();

                    return $html;
                }
                return 'Реинвеста нет ';

            },
            'filter' => [1 => 'Не выполнено ', 0 => 'Выполнено']
        ]
    ],
]);
Pjax::end();