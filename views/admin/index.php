<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\UserSearch;

use yii\widgets\LinkPager;
$this->title = 'Пользователи';
?>
    <div class="row">
        <div class="col-sm-12">
        <?php
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());


        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['label' => '#', 'attribute' => 'id'],
                ['label' => 'Логин', 'attribute' => 'username'],
                ['label' => 'Имя', 'attribute' => 'name'],
                ['label' => 'Фамилия', 'attribute' => 'surname'],
                ['label' => 'Баланс', 'attribute' => 'lc'],
                ['label' => 'Баланс(EUR)', 'attribute' => 'eur'],
                ['label' => 'Дата регистрации', 'attribute' => 'dateReg'],
                [
                    'class' => 'yii\grid\ActionColumn', 'template' => '{update}',
                ],
            ]
        ]);
        ?>
<!--        <table class="table">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th>#</th>-->
<!--                <th>Логин</th>-->
<!--                <th>Имя</th>-->
<!--                <th>Фамилия</th>-->
<!--                <th>Баланс</th>-->
<!--                <th>Редактировать</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr>-->
<!--                --><?php
//                foreach ($users as $model): ?>
<!--                    <th scope="row">--><?php //echo $model->id; ?><!--</th>-->
<!--                    <td>--><?php //echo $model->username; ?><!--</td>-->
<!--                    <td>--><?php //echo $model->name; ?><!--</td>-->
<!--                    <td>--><?php //echo $model->surname; ?><!--</td>-->
<!--                    <td>--><?php //echo $model->lc; ?><!--</td>-->
<!--                    <td><button type="button" class="btn btn-success"><i class="far fa-edit"></i></button></td>-->
<!--                --><?php //endforeach; ?>
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->
<!--            --><?php
//
//            echo LinkPager::widget([
//                'pagination' => $pages,
//            ]);
//
//            ?>
    </div>
    </div>
