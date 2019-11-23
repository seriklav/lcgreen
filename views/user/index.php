<?php
/* @var $this yii\web\View */

$this->title = 'Профиль';
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
?>
<?php
$url = URL::to(['/user/index']);
$js = <<<JS
$('#uploadform-country').on('change', function() {
  $.ajax({
      url: "$url",
      data: {country: $(this).find(":selected").val()},
      type: 'POST',
      success :function(regions) {
            $('#uploadform-region').html(regions);
              $.ajax({
              url: "$url",
              data: {region: $('#uploadform-region').find(":selected").val()},
              type: 'POST',
              success :function(city) {
                $('#uploadform-city').html(city);
              },
              });
      },
      }
  );
}); 

$('#uploadform-region').on('change', function() {
  $.ajax({
      url: "$url",
      data: {region: $(this).find(":selected").val()},
      type: 'POST',
      success :function(city) {
        $('#uploadform-city').html(city);
      },
      }
  );
});

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
JS;
$this->registerJs($js);
?>
<style>
    #user-profile{
        width: 100%;
    }
    .profile-img img{
        width: 200px;
        height: 201px;
    }
    .steps-popup .input-block .label{color: #0f0f0f !important;}
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
                <div class="heading">Профиль участника</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                        <div class="tab active">Профиль участника</div>
                        <div class="tab">Приватность</div>
                        <!-- <div class="tab">Запрет редактирования</div> -->
                        <div class="tab">Лог действий</div>
                        <div class="tab">Баннеры</div>
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont active">
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id' => 'user-profile']]) ?>
                    <div class="padd-block sides">
                        <div class="l-side">
                            <div class="profile-img">
                                <?php if(!Yii::$app->user->identity->avatar) echo Html::img('@web/img/user.png');
                                else echo Html::img('@web/'.Yii::$app->user->identity->avatar); ?>
                            </div>


                            <?= $form->field($model, 'file')->fileInput()->label('Загрузить фотографию',['class'=>'load-img']) ?>


                        </div>

                        <div class="r-side">
                            <div class="input-block">
                                <p>Фамилия <span class="star">*</span></p>
                                <?= $form->field($model, 'surname')
                                    ->textInput(['value' => Yii::$app->user->identity->surname])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Имя <span class="star">*</span></p>
                                <?= $form->field($model, 'name')
                                    ->textInput(['value' => Yii::$app->user->identity->name])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Отчество</p>
                                <?= $form->field($model, 'father')
                                    ->textInput(['value' => Yii::$app->user->identity->father])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Пол</p>
                                <?= $form->field($model, 'sex')
                                    ->dropdownList(array('Мужской','Женский'),['value' => Yii::$app->user->identity->sex])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Дата рождения <span class="star">*</span></p>
                                <?= $form->field($model, 'dateBirth')
                                    ->textInput(['value' => Yii::$app->user->identity->dateBirth])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Страна</p>
                                <?=
                                $form->field($model, 'country')
                                    ->dropdownList($get_country,['options' =>[ Yii::$app->user->identity->country => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Область</p>
                                <?=
                                $form->field($model, 'region')
                                    ->dropdownList($get_region,['options' =>[ Yii::$app->user->identity->region => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Город</p>
                                <?=
                                $form->field($model, 'city')
                                    ->dropdownList($get_city,['options' =>[ Yii::$app->user->identity->city => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Телефон</p>
                                <?= $form->field($model, 'phone')
                                    ->textInput(['value' => Yii::$app->user->identity->phone])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>E-mail <span class="star">*</span></p>
                                <?= $form->field($model, 'email')
                                    ->textInput(['value' => Yii::$app->user->identity->email])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Skype</p>
                                <?= $form->field($model, 'skype')
                                    ->textInput(['value' => Yii::$app->user->identity->skype])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Пароль</p>
                                <?= $form->field($model, 'password')
                                    ->passwordInput(['placeholder' => "Новый пароль"])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Повторите пароль</p>
                                <?= $form->field($model, 'repeat_password')
                                    ->passwordInput(['placeholder' => "Повторите пароль"])
                                    ->label(false) ?>
                            </div>
                            <div class="input-block">
                                <p>Ваш спонсор</p>
                                <input type="text" class="disabled" value="<?= Yii::$app->user->identity->sponsor ?>">
                            </div>
                            <div class="input-block">
                                <p>E-mail код</p>
                                <?= $form->field($model, 'EmailCode')
                                    ->textInput()
                                    ->label(false) ?>
                            </div>
                            <div class="button-block">
                                <input id="send_code" type="button" class="button" value="Получить код">
                            </div>
                        </div>

                    </div>

                    <div class="border-top">
                        <div class="string">
                            <div class="text">Ваша реферальная ссылка</div>
                            <div>
                               <span id="link_data"> <?php echo Yii::$app->urlManager->createAbsoluteUrl(['green/register', 'referal' => Yii::$app->user->identity->username]); ?> </span>
                                <input type="button" class="copy-link" id="copy_data" value="скопировать ссылку">
                            </div>
                        </div>
                        <div class="string">
                            <div class="text">Бесплатные промо-ссылки</div>
                            <div>
                                У вас пока нет бесплатных промо-ссылок
                            </div>
                        </div>
                        <div class="string flex-end">
                            <input type="submit" class="button big" value="Сохранить профиль">
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <div class="tab-cont">
                    <div class="padd-block">
                        <?php $form = ActiveForm::begin() ?>
                        <div class="popup-block">

                            <div class="string">
                                <span>ФИО</span>
                                <?=
                                $form->field($priv_sett, 'fio_vis')
                                    ->dropdownList(array('Виден всем','Не виден никому'),['options' =>[ $priv_sett->fio_vis => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="string">
                                <span>Телефон</span>
                                <?=
                                $form->field($priv_sett, 'phone_vis')
                                    ->dropdownList(array('Виден всем','Не виден никому'),['options' =>[ $priv_sett->phone_vis => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="string">
                                <span>E-mail</span>
                                <?=
                                $form->field($priv_sett, 'email_vis')
                                    ->dropdownList(array('Виден всем','Не виден никому'),['options' =>[ $priv_sett->email_vis => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <div class="string">
                                <span>Skype</span>
                                <?=
                                $form->field($priv_sett, 'skype_vis')
                                    ->dropdownList(array('Виден всем','Не виден никому'),['options' =>[ $priv_sett->skype_vis => ['Selected' => true]]])
                                    ->label(false) ?>
                            </div>
                            <input type="submit" class="button big" value="Сохранить">

                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <!-- <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block">
                            <div class="text-block">С помощью этой формы вы можете запретить изменять свои данные в личном кабинете <br> <b>Внимание!</b> Чтобы вернуть возможность измененния данных, вы должны отправить запрос техподдержке с помощью формы обратной связи</div>
                            <div class="string">
                                <span>Телефон</span>
                                <select name="" id="">
                                    <option value="">Доступно для изменения</option>
                                    <option value="">Не доступно для изменения</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>E-mail</span>
                                <select name="" id="">
                                    <option value="">Доступно для изменения</option>
                                    <option value="">Не доступно для изменения</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>Skype</span>
                                <select name="" id="">
                                    <option value="">Доступно для изменения</option>
                                    <option value="">Не доступно для изменения</option>
                                </select>
                            </div>
                            <input type="button" class="button big" value="Сохранить">
                        </div>
                    </div>
                </div> -->
                 <div class="tab-cont">
                    <div class="padd-block">
                        <div class="scroll-block">
                            <?php
                            $dataProvider = new ActiveDataProvider([
                                'query' => \app\models\UserLogs::find() ->where(['user_id' => Yii::$app->user->identity->id]) ->orderBy(['id' => SORT_DESC]),
                                'pagination' => [
                                    'pageSize' => 25
                                ],
                            ]);
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    ['label' => 'Дата', 'attribute' => 'date'],
                                    ['label' => 'Объект', 'attribute' => 'object'],
                                    ['label' => 'Действие', 'attribute' => 'action'],
                                    ['label' => 'Описание', 'attribute' => 'detail'],
                                    ['label' => 'Сумма',
                                        'value' => function ($data) {
                                        if($data->sum == 0) return '';
                                        return $data->sum;
                                    } ]
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                    </div>
                </div>
            </div>
        </div>
<?php $this->registerJsFile('@web/js/jquery.mask.js',['depends' => 'yii\web\YiiAsset']); ?>
<?php
$js = <<<JS
    $('#uploadform-datebirth').mask('00.00.0000');

    $('#copy_data').on('click',function(){
      var code = document.querySelector('#link_data'); // #text - блок из которого нужно скопировать
      var range = document.createRange();
      range.selectNode(code);
      window.getSelection().addRange(range);
      
      document.execCommand('copy');
      window.getSelection().removeAllRanges();
    });
JS;
$this->registerJs($js);?>