<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<h2>Клоны пользователя</h2>
<?php

echo Html::a('clones', '/web/admin/clones', ['class' => 'btn btn-success']);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'owner_id',
        'step',
        'sponsor',
        'sponsor_id',
        'daily_status',
        [
            'label' => 'steps',
            'format' => 'raw',
            'value' => function($model) {
                $data = $model->getStepsUsersQuery()->asArray() ->all();
                return VarDumper::dumpAsString($data, 100, true);
            }
        ],

        ]
]);