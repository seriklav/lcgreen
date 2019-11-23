<?php
$this->title = 'Обратная связь';
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\FeedBackMod;
?>
<style>
    #feedback{width: 100%;}
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
        <div class="heading">Обратная связь</div>
        <div class="tabs secondary">
            <div class="tab-block">
                <div class="tab">Отправить</div>
                <div class="tab">Входящие</div>
                <div class="tab">Исходящие</div>
<!--                <div class="tab">Оставить отзыв</div>-->
            </div>
        </div>
    </div>
    <div class="tab-content secondary">
        <div class="tab-cont active">
            <div class="padd-block">
                <div class="popup-block flex-start message">
                    <?php $form = ActiveForm::begin(['options' => ['id' => 'feedback']]) ?>
                        <div class="input-log">
                            <div class="string">
                                <span>От кого <sapn class="star">*</sapn></span>
                                <input type="text" class="disabled" value="<?php echo Yii::$app->user->identity->username; ?>">
                            </div>
                            <div class="string">
                                <div class="checkbox for-user" id="for_user">Кому <div>(Логин)</div></div>
                                <input type="text" class="disabled" id="send">
                            </div>
                            <div class="string">
                                <span class="checkbox active support">Техподдержка</span>
                            </div>
                            <div class="string theme">
                                <span>Тема <span class="star">*</span></span>
                                <select class="default support" id="support">
                                    <option value="">Вывод средств</option>
                                    <option value="">Не приходит код подтверждения</option>
                                    <option value="">Изменение личных данных</option>
                                    <option value="">Запрет редактирования данных</option>
                                    <option value="">Заказ лендинга</option>
                                    <option value="">Другое</option>
                                </select>
                                <input type="text" class="hiden support" id="support_title">
                            </div>
                            <div class="string vert-start">
                                <span>Сообщение <span class="star">*</span></span>
                                <textarea name="" id="message"></textarea>
                            </div>
                            <div class="string flex-end">
                                <input type="button" class="button big" id="send_btn" value="Отправить">
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
                    <div class="alert alert-danger alert-dismissible hide" id="error">
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="scroll-block">
                    <?php
                    $dataProvider = new ActiveDataProvider([
                        'query' => FeedBackMod::find()  ->filterWhere([
                            'new' => 1]) ->andFilterWhere(['<>','last_send', Yii::$app->user->identity->username]) ->orWhere(['who' => -1]),
                        'pagination' => [
                            'pageSize' => 20,
                        ],
                    ]);
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'showOnEmpty' => true,
                        'summary' => false,
                        'columns' => [
                            ['label' => '#', 'attribute' => 'id'],
                            ['label' => 'Дата', 'attribute' => 'update_date'],
                            [
                                'label' => 'От кого',
                                'value' => function ($data)
                                {
                                    if($data->last_send == -1) return 'Техподдержка';
                                    else return $data->last_send;
                                },
                            ],
                            ['label' => 'Тема', 'attribute' => 'title'],
                            [
                                'format' => 'html',
                                'label' => 'Диалог',
                                'value' => function ($data) {
                                    $dialog = '<a href="'.URL::to(['/user/dialog?id='.$data->id]).'">Открыть</a>';
                                    return $dialog;
                                },
                            ],
                        ]
                    ]);
                    ?>
                </div>

            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="scroll-block">
                    <?php
                        $dataProvider = new ActiveDataProvider([
                            'query' => FeedBackMod::find() ->where(['who' => Yii::$app->user->identity->username]),
                            'pagination' => [
                                'pageSize' => 20,
                            ],
                        ]);
                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'showOnEmpty' => true,
                            'summary' => false,
                            'columns' => [
                                ['label' => '#', 'attribute' => 'id'],
                                ['label' => 'Дата', 'attribute' => 'send_date'],
                                [
                                        'label' => 'Кому',
                                        'value' => function ($data)
                                        {
                                            if($data->send == -1) return 'Техподдержка';
                                            else return $data->send;
                                        },
                                    ],
                                ['label' => 'Тема', 'attribute' => 'title'],
                                [
                                    'format' => 'html',
                                    'label' => 'Диалог',
                                    'value' => function ($data) {
                                        $dialog = '<a href="'.URL::to(['/user/dialog?id='.$data->id]).'">Открыть</a>';
                                       return $dialog;
                                    },
                                ],
                            ]
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$url = URL::to(['/user/feedback']);
$js = <<<JS
$('#send_btn').on('click', function(event) {
    event.preventDefault();
    var send = null;
    var title = null;
    if($('#for_user').hasClass( "active" ))
    {
        send = $('#send').val();
        title = $('#support_title').val();
    }
    else {
        send = -1;
        title = $('#support option:selected').text();
    }
      $.ajax({
          url: "$url",
          data: {action: "send_mess", send: send, title: title, message: $('#message').val() },
          type: 'POST',
          success :function(data) {
                if(data.indexOf("error") != -1){
                   data = data.replace("error:", "");
                   $('#error').removeClass('hide').html(data); 
                }
               if(data == 'ok'){
                  $('#error').hide();  
                  $('#feedback').html('Сообщение успешно отправлено!');
               }
            },
          }
      );
});
JS;
$this->registerJs($js);
?>