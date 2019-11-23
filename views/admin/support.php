<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Техподдержка';
$newurl = URL::to(['/admin/newdialog']);
?>
<div class="row">
    <div class="col-sm-12">
        <a style="float: right;" class="btn btn-primary" href="<?php echo $newurl; ?>">Создать новый диалог</a>
        <br><br><br>
        <?php
        $dataProvider = new ActiveDataProvider([
            'query' => \app\models\FeedBackMod::find() ->where(['send' => -1]) ->orWhere(['who' => -1]) ->orderBy(['update_date' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 50
            ],
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                [
                    'label' => 'Пользовтаель',
                    'value' => function ($data)
                    {
                        if($data->who == -1) return 'Техподдержка';
                        else return $data->who;
                    },
                ],
                ['label' => 'Тема', 'attribute' => 'title'],
                ['label' => 'Дата', 'attribute' => 'update_date'],
                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{dialog}',
                    'buttons' => [
                        'dialog' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-envelope"></span>', $url, [
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
    </div>
</div>
