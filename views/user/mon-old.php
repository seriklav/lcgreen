<?php
$this->title = 'Мониторинг';
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\models\StepUsers;
use app\models\Step;
use app\components\Tree;
?>
<style>
    .tab-content.secondary .cycle{
        cursor: pointer;
    }
    .ui-widget-overlay{
        opacity: 0.3;
    }
</style>
<style>
    .grid-view{
        max-width: 1040px;
        min-width: 940px;
        width: 100%;
        margin: 0 auto 70px;
        padding: 0 15px;
    }
    .grid-view th,.grid-view th a{
        background: #53a510;
        color: #fff;
    }
</style>
<div class="tab-cont active">
    <div class="padd-block">
        <div class="heading">Мониторинг</div>
        <div class="tabs secondary">
            <div class="tab-block">
                <div class="tab">Параметры</div>
                <div class="tab">Циклы</div>
                <div class="tab">Иерархия</div>
            </div>
        </div>
    </div>
    <div class="tab-content secondary">
        <div class="tab-cont active">
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
                                ->getReferals(Yii::$app->user->identity->username)->orderBy(['id' => SORT_DESC]);
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
                                ->getReferals(Yii::$app->user->identity->username)->orderBy(['id' => SORT_DESC]);
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
        </div>
        <div class="tab-cont" id="step_act">
            <!-- <div class="padd-block">
                <div class="params-block none">
                    <div class="string">
                        <div class="label">Логин</div>
                        <input type="text">
                    </div>
                    <div class="string">
                        <div class="label">ФИО</div>
                        <input type="text">
                    </div>
                    <input type="button" class="button big" value="Поиск">
                </div>
            </div> -->
            <div class="padd-block">
                <div class="cycle-block">
                    <?php if(Yii::$app->session->hasFlash('message')): ?>
                        <div class="alert alert-danger alert-dismissible show" role="alert">
                            <?php echo Yii::$app->session->getFlash('message'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="string flex centered vert-start wrap">
                        <?php for($i = 0; $i < 4; $i++){ ?>
                            <div class="cycle" data-cycle="<?php echo $i; ?>">
                                <div class="title"><?php echo $step_name[$i]; ?></div>
                                <?php if(is_null(Step::findOne(['owner_id' => Yii::$app->user->identity->id,'step' => $i]))): ?>  <div class="summ"> <?php echo $step_price[$i]; ?> </div> <?php else: echo '<span class="have"></span>'; ?> <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="string flex centered vert-start wrap">
                        <?php for($i = 4; $i < 10; $i++){ ?>
                            <div class="cycle" data-cycle="<?php echo $i; ?>">
                                <div class="title"><?php echo $step_name[$i]; ?></div>
                                <?php if(is_null(Step::findOne(['owner_id' => Yii::$app->user->identity->id,'step' => $i]))): ?> <div class="summ"><?php echo $step_price[$i]; ?> </div> <?php else: echo '<span class="have"></span>'; ?> <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="exit-user flex vert-start wrap hide">
                    <?php $out_check = 0; foreach ($out_user as $item):
                        $yes = 0;
                        foreach ($all as $sal) {
                            if($item->owner_id == $sal->id){  $yes = 1;  break;}
                        }
                        if(!$yes) continue;
                        $out_check++;
                        $user = User::findOne(['id' => $item->owner_id]); if($item->step != $user->step) continue;?>
                        <div class="comand">
                            <div class="block-logo" style="width: 100px; min-height: 100px;">
                                <div class="user-logo" style="width:90px;height auto; background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                else echo '../../web/'.$user->avatar; ?>')"></div>
                            </div>
                            <div class="data">
                                <div class="ident"> <?php echo $user->username; ?> </div>
                                <div class="name"> <?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?> </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if($out_check): ?>
                    <button type="button" id="load_exit_user" class="btn" style="background: #df8545; color:#fff;">Вышедшие аккаунты</button>
                <?php endif; ?>
            </div>
            <?php if($out): ?>
                <div class="padd-block">
                    <div class="flex centered comand">
                        <div class="flex column centered">
                            <div class="block-logo">
                                <div class="user-logo" style="background-image: url('<?php if(!Yii::$app->user->identity->avatar) echo '../../web/img/user.png';
                                else echo '../../web/'.Yii::$app->user->identity->avatar; ?>')"></div>
                            </div>
                            <div class="data">
                                <div class="ident"><?php echo Yii::$app->user->identity->username;?></div>
                                <div class="name"><?php echo Yii::$app->user->identity->name; echo " ".Yii::$app->user->identity->surname; echo " ".Yii::$app->user->identity->father;?></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex centered comand wrap">
                        <?php $loop = $step_place[Yii::$app->user->identity->step_mon] - 1;
                        $seven = array();
                        if($step_place[Yii::$app->user->identity->step_mon] == 7) $loop = 2;  ?>
                        <?php for($i = 0; $i < $loop; $i++){ ?>
                            <div class="block-logo">
                                <?php if($teamed && count($teamed) > $i): $user = User::findOne(['id' => $teamed[$i]->user_id]); $seven[] = $user->id;?>
                                    <div class="user-logo" style="background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                    else echo '../../web/'.$user->avatar; ?>')"></div>
                                    <div class="data">
                                        <div class="ident"> <?php echo $user->username;?> </div>
                                        <div class="name"><?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                    if($loop == 2):
                        ?> <div class="flex centered comand wrap">
                        <?php
                        for($i = $loop; $i < $step_place[Yii::$app->user->identity->step_mon] - 1; $i++){ ?>
                            <div class="block-logo">
                                <?php
                                if(($i == 2 || $i == 3) && @$seven[0])
                                {
                                    $minus = 2;
                                    $seven_step = Step::findOne([
                                        'owner_id' => $seven[0],
                                        'status' => 0,
                                        'step' =>  Yii::$app->user->identity->step_mon
                                    ]);
                                    $seven_users = StepUsers::find()
                                        ->select(['step_id','user_id','id'])
                                        ->where(['step_id' => $mon_step])
                                        ->andWhere(['seven' => $seven[0]])
                                        ->all();

                                }
                                if(($i == 4 || $i == 5) && @$seven[1])
                                {
                                    $minus = 4;
                                    $seven_step = Step::findOne([
                                        'owner_id' => $seven[1],
                                        'status' => 0,
                                        'step' =>  Yii::$app->user->identity->step_mon
                                    ]);
                                    $seven_users = StepUsers::find()
                                        ->select(['step_id','user_id','id'])
                                        ->where(['step_id' => $mon_step])
                                        ->andWhere(['seven' => $seven[1]])
                                        ->all();
                                }
                                if(@$seven_users[$i - $minus]):
                                    ?>

                                    <?php $user = User::findOne(['id' => $seven_users[$i - $minus]->user_id]);?>
                                    <div class="user-logo" style="background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                    else echo '../../web/'.$user->avatar; ?>')"></div>
                                    <div class="data">
                                        <div class="ident"> <?php echo $user->username;?> </div>
                                        <div class="name"><?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php endif; ?>


                </div>
            <?php endif; ?>
            <?php if($users_step): ?>
                <div class="padd-block">
                    <?php
                    $all_step = array();
                    $loop = $step_place[Yii::$app->user->identity->step_mon] - 1;
                    if($step_place[Yii::$app->user->identity->step_mon] == 7) $loop = 2;
                    foreach ($users_step as $step)
                    {
                        $users = StepUsers::find()
                            ->select(['step_id','user_id','id'])
                            ->where(['step_id' => $step->id])
                            ->andWhere(['seven' => 0])
                            ->all();

                        foreach ($users as $user) {
                            @$all_step[$step->id][] = $user->user_id;
                        }
                    }
                    $item = 0;
                    foreach ($users_step as $step) {
                        // if(Yii::$app->user->identity->role == 0){
                        $yes = 0;
                        foreach ($all as $sal) {
                            if($step->owner_id == $sal->id){  $yes = 1;  break;}
                        }
                        if(!$yes) continue;
                        // }
                        $seven = array();
                        if($item == 0):
                            ?>
                            <?php $user = User::findOne(['id' => $step->owner_id]);?>
                            <div class="flex centered comand users" data-user-id="<?php echo $user->id; ?>">
                                <div class="flex column centered">
                                    <div class="block-logo">
                                        <div class="user-logo" style="background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                        else echo '../../web/'.$user->avatar; ?>')"></div>
                                    </div>
                                    <div class="data">
                                        <div class="ident"> <?php echo $user->username;?> </div>
                                        <div class="name"><?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; $item++; ?>
                        <div class="flex centered comand wrap step_users">
                            <?php for($i = 0; $i < $loop; $i++){ ?>
                                <div class="block-logo">
                                    <?php if(@$all_step[$step->id][$i]): $user = User::findOne(['id' => $all_step[$step->id][$i]]); $seven[] = $user->id;?>
                                        <div class="user-logo" style="background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                        else echo '../../web/'.$user->avatar; ?>')"></div>
                                        <div class="data">
                                            <div class="ident"> <?php echo $user->username;?> </div>
                                            <div class="name"><?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php
                        if($loop == 2):
                            ?> <div class="flex centered comand wrap step_users">
                            <?php
                            for($i = $loop; $i < $step_place[Yii::$app->user->identity->step_mon] - 1; $i++){ ?>
                                <div class="block-logo">
                                    <?php
                                    if(($i == 2 || $i == 3) && @$seven[0])
                                    {
                                        $minus = 2;
                                        $seven_step = Step::findOne([
                                            'owner_id' => $seven[0],
                                            'step' =>  Yii::$app->user->identity->step_mon
                                        ]);
                                        $seven_step = Step::findOne([
                                            'owner_id' => $seven_step->sponsor_id,
                                            'step' =>  Yii::$app->user->identity->step_mon
                                        ]);
                                        $seven_users = StepUsers::find()
                                            ->select(['step_id','user_id','id'])
                                            ->where(['step_id' => $seven_step->id])
                                            ->andWhere(['seven' => $seven[0]])
                                            ->all();
                                    }
                                    if(($i == 4 || $i == 5) && @$seven[1])
                                    {
                                        $minus = 4;
                                        $seven_step = Step::findOne([
                                            'owner_id' => $seven[1],
                                            'step' =>  Yii::$app->user->identity->step_mon
                                        ]);
                                        $seven_step = Step::findOne([
                                            'owner_id' => $seven_step->sponsor_id,
                                            'step' =>  Yii::$app->user->identity->step_mon
                                        ]);
                                        $seven_users = StepUsers::find()
                                            ->select(['step_id','user_id','id'])
                                            ->where(['step_id' => $seven_step->id])
                                            ->andWhere(['seven' => $seven[1]])
                                            ->all();
                                    }
                                    if(@$seven_users[$i - $minus]):
                                        ?>

                                        <?php $user = User::findOne(['id' => $seven_users[$i - $minus]->user_id]);?>
                                        <div class="user-logo" style="background-image: url('<?php if(!$user->avatar) echo '../../web/img/user.png';
                                        else echo '../../web/'.$user->avatar; ?>')"></div>
                                        <div class="data">
                                            <div class="ident"> <?php echo $user->username;?> </div>
                                            <div class="name"><?php echo $user->name; echo " ".$user->surname; echo " ".$user->father;?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php endif; ?>
                        <?php $item = 0; $loop = $step_place[Yii::$app->user->identity->step_mon] - 1; if($step_place[Yii::$app->user->identity->step_mon] == 7) $loop = 2; ?>
                    <?php } ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <?php
                global $kek;
                $kek = array();
                $piza = array();
                $counter = array();
                foreach ($all as $a)
                {
                    @$piza[$a->sponsor][] = $a;
                    @$counter[$a->sponsor]++;
                }
                ?>
                <?php

                function RefTree($username, $name, $surname, $father, $items, $num = 0, $counter = array())
                {
                    global $kek;
                    $kek[] = $username;
                    echo '<div class="profile-block mini">
                    <div class="string">
                        <div class="folder"></div>
                        <div class="name">'.$username.' ' .$name. ' ' .$surname. ' ' .$father .'</div>
                        <div class="search"></div>
                    </div>';

                    if(@$items[$username])
                    {
                        foreach(@$items[$username] as $item)
                        {
                            RefTree($item->username,$item->name,$item->surname,$item->father,$items,$num + 1, $counter);
                        }
                    }
                    echo '</div>';
                    if(@$counter[$username] >= $num) return 1;
                }
                $count = count($all);
                for ($i = 0; $i < $count; $i++) {
                    if($all[$i]->username == Yii::$app->user->identity->username) continue;
                    if(!in_array($all[$i]->username, $kek))  RefTree($all[$i]->username,$all[$i]->name,$all[$i]->surname,$all[$i]->father,$piza,0,$counter)
                    ?>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div id="dialog-form" title="Вступление в цикл">
    <?php $form = ActiveForm::begin(['options' => ['id' => 'user-step']]) ?>
    <div class="input-block">
        <p>Логин спонсора</p>
        <?= $form->field($model, 'sponsor')
            ->textInput(['value' => Yii::$app->user->identity->sponsor])
            ->label(false) ?>
    </div>
    <div class="input-block">
        <p>E-mail код</p>
        <?= $form->field($model, 'emailCode')
            ->textInput()
            ->label(false) ?>
        <div class="button-block">
            <input id="send_code" type="button" class="btn btn-secondary" value="Получить код">
        </div>
        <input type="hidden" name="number" id="number_step">
    </div>
    <div class="input-block" style="padding: 20px 0 0 0; box-sizing: border-box;">
        <input type="submit" class="button" value="Вступить">
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div id="dialog-set-user" title="Поставить человека в цикл">
    <?php if(Yii::$app->user->identity->role == 1): ?>
        <?php $form = ActiveForm::begin(['options' => ['id' => 'user-step']]) ?>
        <div class="input-block">
            <p>Логин пользователя</p>
            <?= $form->field($user_set, 'username')
                ->textInput()
                ->label(false) ?>
        </div>
        <input type="hidden" id="set_user" name="set_user">
        <div class="input-block" style="padding: 20px 0 0 0; box-sizing: border-box;">
            <input type="submit" class="button" name="set_player" value="Поставить">
        </div>
        <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>
<?php

$this->registerJsFile('@web/js/jquery-ui.min.js',['depends' => 'yii\web\YiiAsset']);
$this->registerCssFile('@web/css/jquery-ui.min.css',['depends' => 'yii\web\YiiAsset']);

$url = URL::to(['/user/monitoring']);
$js = <<<JS
$('#send_code').on('click', function(event ) {
    event.preventDefault();
      $.ajax({
          url: "$url",
          data: {email: "send"},
          type: 'POST',
          success :function() {
            alert('На ваш Email был выслан код подтверждения!');
            },
          }
      );
});

$('#load_exit_user').on('click', function(event ) {
    event.preventDefault();
    $('.exit-user').removeClass( "hide");
    $(this).remove();
});

$('#search_user').on('click', function(event ) {
    event.preventDefault();
      $.ajax({
          url: "$url",
          data: {search: 1, login: $('#login').val(), fio: $('#fio').val(), sponsor: $('#sponsor').val(), phone: $('#phone').val(), status: $('#status').val(), emails: $('#emails').val(), dreg: $('#dreg').val() },
          type: 'POST',
          success :function(data) {
               $('#table-block').html(data);
            },
          }
      );
});

var dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
    });

    $('.tab-content.secondary .cycle').on('click',function(){
        if($( this ).children('.have').length) 
        {
      $.ajax({
          url: "$url",
          data: {number: $(this).data('cycle')},
          type: 'POST',
              success :function(mess) 
              {
                  location.href = "#mon";
                  location.reload();
              },
          }
      );
            return;
        }
        $( "#number_step" ).val($(this).data('cycle'));
        dialog.dialog( "open" );
    });
    if(location.href.includes( '#mon' )){
        $('.tab-content.secondary .tab-cont.active').removeClass('active');
        $('#step_act').addClass('active');
    }
JS;
$this->registerJs($js);

if(Yii::$app->user->identity->role == 1)
    $js = <<<JS
     var set_user = $( "#dialog-set-user" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
    });
     $('.step_users').on('click',function(){
            $('#set_user').val($( this ).prev('.users').data('user-id'));
            set_user.dialog( "open" );
    });
JS;
$this->registerJs($js);
?>

