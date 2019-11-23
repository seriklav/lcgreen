<?php
    use app\models\User;

?>
<div class="padd-block">
    <div class="params-block">
        <div class="string">
            <div class="label">Логин</div>
            <input type="text" id="login">
        </div>
        <div class="string">
            <div class="label">ФИО</div>
            <input type="text" id="fio">
        </div>
        <div class="string">
            <div class="label">Спонсор</div>
            <input type="text" id="sponsor">
        </div>
        <div class="string">
            <div class="label">Телефон</div>
            <input type="text" id="phone">
        </div>
        <div class="string">
            <div class="label">Почта</div>
            <input type="text" id="emails">
        </div>
        <div class="string">
            <div class="label">Дата регистрации</div>
            <input type="text" id="dreg">
        </div>
        <div class="string">
            <div class="label">Статус</div>
            <select name="" id="status">
                <option value="0">Все пользователи</option>
                <option value="1">Активные пользователи</option>
                <option value="2">Не активные пользователи</option>
            </select>
        </div>
        <input type="button" class="button big" id="search_user" value="Поиск">
    </div>
    <div class="scroll-block">
        <div class="table-block" id="table-block">

            <?php

            if(Yii::$app->getRequest()->getQueryParam('page3') || Yii::$app->getRequest()->getQueryParam('page1') || Yii::$app->getRequest()->getQueryParam('page2')) {
                $session = Yii::$app->session;
                if (!$session->isActive) $session->open();
                $pages = '';
                if (Yii::$app->getRequest()->getQueryParam('page3')) {
                    $search = $session->get('search_page3');
                    $pages = "page3";
                }
                if (Yii::$app->getRequest()->getQueryParam('page2')) {
                    $search = $session->get('search_page2');
                    $pages = "page2";
                }
                if (Yii::$app->getRequest()->getQueryParam('page1')) {
                    $search = $session->get('search_page1');
                    $pages = "page1";
                }
                $session->close();
                if(!$search)  $search = User::sponsor()
                    ->select(['dateReg', 'username', 'name', 'surname', 'father', 'email', 'skype', 'phone'])
                    ->getReferals(Yii::$app->user->identity->username, true)->orderBy(['id' => SORT_DESC]);
                $dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $search,
                    'sort' => false,
                    'pagination' => [
                        'pageSize' => 25,
                        'pageParam' => $pages,
                    ],
                ]);
            }
            else{
                $teams = User::sponsor()
                    ->select(['dateReg', 'username', 'name', 'surname', 'father', 'email', 'skype', 'phone'])
                    ->getReferals(Yii::$app->user->identity->username, true)->orderBy(['id' => SORT_DESC]);
                $dataProvider = new \yii\data\ActiveDataProvider([
                    'query' => $teams,
                    'sort' =>false,
                    'pagination' => [
                        'pageSize' => 25,
                        'pageParam' => 'page1',
                    ],
                ]);
            }
            echo  \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['label' => 'Дата регистрации', 'attribute' => 'dateReg'],
                    ['label' => 'Логин', 'attribute' => 'username'],
                    [
                        'label' => 'ФИО',
                        'value' => function ($data)
                        {
                            return $data->name.' '.$data->surname.' '.$data->father ;
                        },
                    ],
                    ['label' => 'E-mail', 'attribute' => 'email'],
                    ['label' => 'Телефон', 'attribute' => 'phone'],
                    ['label' => 'Skype', 'attribute' => 'skype'],
                    ['label' => 'Рефералов', 'value' => function ($data)
                    {
                        $count = User::find()
                            ->where(['sponsor' => $data->username])
                            ->count();
                        return $count;
                    }
                    ]
                ]
            ]); ?>

        </div>
    </div>
</div>