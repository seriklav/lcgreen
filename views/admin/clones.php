<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CloneSearch */
/* @var $dataProviderClones yii\data\ActiveDataProvider */
?>

<h2>Клоны пользователя</h2>
<?php



echo GridView::widget([
    'dataProvider' => $dataProviderClones,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        'name',
        'sponsor',
        [
            'label' => 'steps',
            'format' => 'raw',
            'value' => function($model) {
                return Html::a('steps', Url::to(['/admin/clone-steps', 'cloneId' => $model->id]), ['class' => 'btn btn-info']);
            }
        ],
        ]
]);