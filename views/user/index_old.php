<?php
/* @var $this yii\web\View */

$this->title = 'Профиль';
use yii\bootstrap\ActiveForm;
use app\models\RegisterForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php
$url = URL::to(['/user/index']);
$js = <<<JS
$('#registerform-country').on('change', function() {
  $.ajax({
      url: "$url",
      data: {country: $(this).find(":selected").val()},
      type: 'POST',
      success :function(regions) {
            $('#registerform-region').html(regions);
              $.ajax({
              url: "$url",
              data: {region: $('#registerform-region').find(":selected").val()},
              type: 'POST',
              success :function(city) {
                $('#registerform-city').html(city);
              },
              });
      },
      }
  );
}); 

$('#registerform-region').on('change', function() {
  $.ajax({
      url: "$url",
      data: {region: $(this).find(":selected").val()},
      type: 'POST',
      success :function(city) {
        $('#registerform-city').html(city);
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
    .steps-popup .input-block .label{color: #0f0f0f !important;}
</style>
     <div class="tab-cont active">
            <div class="padd-block">
                <div class="heading">Профиль участника</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                        <div class="tab active">Профиль участника</div>
                        <div class="tab">Приватность</div>
                        <div class="tab">Запрет редактирования</div>
                        <div class="tab">Лог действий</div>
                        <div class="tab">Баннеры</div>
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont active">
                    <div class="padd-block sides">
                        <div class="l-side">
                            <div class="profile-img">
                                <?php echo Html::img('@web/img/user.png') ?>
                            </div>
                            <label class="load-img">
                                Загрузить фотографию
                                <input type="file">
                            </label>
                        </div>
                        <?php $form = ActiveForm::begin(['id' => 'user-profile']); ?>
                        <?php $model = new RegisterForm(); ?>
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
<!--                            <div class="input-block">-->
<!--                                <p>Пароль</p>-->
<!--                                <input type="password">-->
<!--                            </div>-->
<!--                            <div class="input-block">-->
<!--                                <p>Повторите пароль</p>-->
<!--                                <input type="password">-->
<!--                            </div>-->
                            <div class="input-block">
                                <p>Ваш спонсор</p>
                                <?= $form->field($model, 'sponsor')
                                    ->textInput(['class' => 'disabled','value' => Yii::$app->user->identity->sponsor])
                                    ->label(false) ?>
                            </div>
<!--                            <div class="input-block">-->
<!--                                <p>E-mail код</p>-->
<!--                                <input type="text">-->
<!--                            </div>-->
<!--                            <div class="button-block">-->
<!--                                <input type="button" class="button" value="Получить код">-->
<!--                                <input type="button" class="button" value="Новый код">-->
<!--                            </div>-->
                        </div>
                        <?php ActiveForm::end(); ?>
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
                            <input type="button" class="button big" value="Сохранить профиль">
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block">
                            <div class="string">
                                <span>ФИО</span>
                                <select name="" id="">
                                    <option value="">Виден всем</option>
                                    <option value="">Не виден никому</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>Телефон</span>
                                <select name="" id="">
                                    <option value="">Виден всем</option>
                                    <option value="">Не виден никому</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>E-mail</span>
                                <select name="" id="">
                                    <option value="">Виден всем</option>
                                    <option value="">Не виден никому</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>Skype</span>
                                <select name="" id="">
                                    <option value="">Виден всем</option>
                                    <option value="">Не виден никому</option>
                                </select>
                            </div>
                            <input type="button" class="button big" value="Сохранить">
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
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
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="input-log">
                            <div class="string">
                                <span>Дата</span>
                                <input type="text">
                            </div>
                            <div class="string">
                                <span>Объект</span>
                                <select class="change-options">
                                    <option value="">---</option>
                                    <option value="user">Пользователь</option>
                                    <option value="check">Счет</option>
                                    <option value="bonus">Бонус</option>
                                </select>
                            </div>
                            <div class="string">
                                <span>Действие</span>
                                <select class="default">
                                    <option value="">Сначала выберите обьект</option>
                                </select>
                                <select class="changed user">
                                    <option value="">Вход в личный кабинет</option>
                                    <option value="">Выход из личного кабинета</option>
                                    <option value="">Изменения профиля</option>
                                    <option value="">Вступление в цикл</option>
                                    <option value="">Выход из цикла</option>
                                    <option value="">Регистрация в системе</option>
                                    <option value="">Смена спонсора</option>
                                </select>
                                <select class="changed check">
                                    <option value="">Реферальный бонус</option>
                                    <option value="">Бонус цикла</option>
                                    <option value="">Перевод на благотворительность</option>
                                    <option value="">Перевод средств</option>
                                    <option value="">Пополнение счета</option>
                                    <option value="">Вступление в цикл</option>
                                    <option value="">Разморозка бонуса цикла</option>
                                    <option value="">Reward</option>
                                    <option value="">Единоразовое вознаграждение</option>
                                    <option value="">Отмена реферального бонуса</option>
                                    <option value="">Отмена досрочной разморозки</option>
                                    <option value="">Возврат средств за выход из цикла</option>
                                    <option value="">Отмена перевода средств</option>
                                    <option value="">Отмена пополнения счета</option>
                                    <option value="">Доступно для изменения</option>
                                    <option value="">Отмена перевода в благотворительный фонд</option>
                                    <option value="">Вывод средств</option>
                                    <option value="">Пополнение счета (Perfect Money USD)</option>
                                    <option value="">Пополнение счета (промо)</option>
                                    <option value="">Блокировка средств</option>
                                    <option value="">Списание средств</option>
                                </select>
                                <select class="changed bonus">
                                    <option value="">Начисление LC бонуса</option>
                                    <option value="">Начисление звезд</option>
                                    <option value="">Добавление промо-аккаунта</option>
                                    <option value="">Активация Star Bonus</option>
                                </select>
                            </div>
                            <div class="string flex-end">
                                <input type="button" class="button big" value="Поиск">
                            </div>
                        </div>
                        <div class="scroll-block">
                            <div class="table-block">
                                <div class="row heading">
                                    <div class="cell date up">Дата <span class="up">&#8593;</span><span class="down">&#8595;</span></div>
                                    <div class="cell object">Объект</div>
                                    <div class="cell action">Действие</div>
                                    <div class="cell summ">Сумма</div>
                                    <div class="cell desc">Описание</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell object">Пользователь</div>
                                    <div class="cell action">Вход в личный кабинет</div>
                                    <div class="cell summ"></div>
                                    <div class="cell desc">Login IP: 178.150.*.*</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell object">Пользователь</div>
                                    <div class="cell action">Вход в личный кабинет</div>
                                    <div class="cell summ"></div>
                                    <div class="cell desc">Login IP: 178.150.*.*</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell object">Пользователь</div>
                                    <div class="cell action">Вход в личный кабинет</div>
                                    <div class="cell summ"></div>
                                    <div class="cell desc">Login IP: 178.150.*.*</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell object">Пользователь</div>
                                    <div class="cell action">Вход в личный кабинет</div>
                                    <div class="cell summ"></div>
                                    <div class="cell desc">Login IP: 178.150.*.*</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell object">Счёт</div>
                                    <div class="cell action">Вступление в цикл</div>
                                    <div class="cell summ">-700</div>
                                    <div class="cell desc">Вступление в цикл</div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination">
                            <div class="page active">1</div>
                            <div class="page">2</div>
                            <div class="page">3</div>
                        </div>
                        <div class="flex space-between export-show">
                            <div class="flex vert-center">
                                <span>Экспорт выписки</span>
                                <input type="button" class="exel">
                                <input type="button" class="pdf">
                            </div>
                            <div class="flex vert-center">
                                <span>Показывать по</span>
                                <select name="" id="" class="green">
                                    <option value="">5</option>
                                    <option value="">10</option>
                                    <option value="">20</option>
                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="heading">Бизнес-группа</div>
                <div class="tabs secondary">
                    <div class="tab-block"></div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="flex centered comand wrap">
                            <div class="title">Цикл Start 1</div>
                        </div>
                        <div class="flex centered comand wrap">
                            <div class="flex column centered">
                                <div class="block-logo">
                                    <div class="user-logo" style="background-image: url('../img/user.png')"></div>
                                </div>
                                <div class="data">
                                    <div class="ident">aa42726</div>
                                    <div class="name">Мишкина Маша Михайловна</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex centered comand wrap">
                            <div class="block-logo"></div>
                            <div class="block-logo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="heading">Информация по счету</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                        <div class="tab">Баланас</div>
                        <div class="tab">Внутренний перевод</div>
                        <div class="tab">Вывод средств</div>
                        <div class="tab">Пополнение счёта</div>
                        <div class="tab">История операций</div>
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont">
                    <div class="padd-block balance">
                        <div class="table-block balance">
                            <div class="row table">
                                <div class="heading">Баланс</div>
                                <div class="cell">0 LC</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Заблокированно средств</div>
                                <div class="cell">0 LC</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Ваш спонсор</div>
                                <div class="cell">OG02437</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Уровень</div>
                                <div class="cell">-</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Рефералов</div>
                                <div class="cell">1</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Структура</div>
                                <div class="cell">1</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Новых партнеров</div>
                                <div class="cell">0</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Счет открыт</div>
                                <div class="cell">14/06/2018</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Дней в проекте</div>
                                <div class="cell">74</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Доход за весь период</div>
                                <div class="cell">0 LC</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Доход за предыдущий месяц</div>
                                <div class="cell">0 LC</div>
                            </div>
                            <div class="row table">
                                <div class="heading">Доход за текущий месяц</div>
                                <div class="cell">0 LC</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block sides">
                            <div class="l-col">
                                <div class="label">От пользователя</div>
                                <select name="" id="">
                                    <option value="">АА42726</option>
                                    <option value="">АА42727</option>
                                </select>
                                <div class="button-block">
                                    <input type="button" class="button" value="На мои аккаунты">
                                    <input type="button" class="button" value="На другие аккаунты">
                                </div>
                                <div class="label">Получатель</div>
                                <select name="" id="">
                                    <option value="">АА42726</option>
                                    <option value="">АА42727</option>
                                </select>
                                <div class="label">Сумма <span class="star">*</span></div>
                                <input type="text">
                                <span>LC</span>
                                <input type="button" class="button big" value="Отправить">
                            </div>
                            <div class="r-col">
                                <div class="info">Внутренние переводы участникам проекта происходят мгновенно и не облагаются комиссией.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block">
                            <div class="text-block">Сумма для вывода должна быть кратна 100 и вводится без учета комиссии. <br> Комиссия за вывод средств составляет 5%</div>
                            <div class="text-block">Вывод средств на любые платежные системы может занимать 1-15 рабочих дней. <br> Средства с кабинета списываются автоматически.</div>
                            <div class="string v2">
                                <span>Сумма <span class="star">*</span></span>
                                <div class="summ">
                                    <input type="text">
                                    <span>LC</span>
                                </div>
                            </div>
                            <div class="string v2 padd-right">
                                <span>Способ вывода <span class="star">*</span></span>
                                <select name="" id="">
                                    <option value="">Доступно для изменения</option>
                                    <option value="">Не доступно для изменения</option>
                                </select>
                            </div>
                            <div class="string v2 padd-right">
                                <span>НОМЕР СЧЕТА <span class="star">*</span></span>
                                <input type="text">
                            </div>
                            <input type="button" class="button big" value="Отправить">
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block">
                            <div class="text-block bold">
                                Текущий курс: 1 USD = 66.17 LC
                            </div>
                            <div class="text-block">Платеж будет осуществлен через систему Perfect Money. <br> Сумма к оплате указывается без учета коммиссии</div>
                            <div class="string v2">
                                <span>Сумма внесения <span class="star">*</span></span>
                                <div class="summ">
                                    <input type="text">
                                    <span>LC</span>
                                </div>
                            </div>
                            <div class="string v2">
                                <span>К оплате</span>
                                <div class="summ">
                                    0 USD
                                </div>
                            </div>
                            <div class="string v2 padd-right">
                                <span>Платежный сервис</span>
                                <select name="" id="">
                                    <option value="">Perfect Money</option>
                                    <option value="">Advansed Cash</option>
                                </select>
                            </div>
                            <input type="button" class="button big" value="Отправить">
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="input-string">
                            <div class="label-block">
                                <div class="label">Начальная дата</div>
                                <input type="text">
                            </div>
                            <div class="label-block">
                                <div class="label">Начальная дата</div>
                                <input type="text">
                            </div>
                            <input type="button" class="button" value="Поиск">
                        </div>
                        <div class="scroll-block">
                            <div class="table-block">
                                <div class="row heading">
                                    <div class="cell date up">Дата <span class="up">&#8593;</span><span class="down">&#8595;</span></div>
                                    <div class="cell summ">Сумма</div>
                                    <div class="cell action">Детали операции</div>
                                    <div class="cell object">Игровой баланс</div>
                                    <div class="cell desc">Номер транзакции</div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell summ">-700.00</div>
                                    <div class="cell action">Вступление в цикл</div>
                                    <div class="cell object">0.00</div>
                                    <div class="cell desc"></div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell summ">-700.00</div>
                                    <div class="cell action">Перевод средств АА26004</div>
                                    <div class="cell object">700.00</div>
                                    <div class="cell desc"></div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell summ">+1400.00</div>
                                    <div class="cell action">Перевод средств АА26004</div>
                                    <div class="cell object">1400.03</div>
                                    <div class="cell desc"></div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell summ">+1400.00</div>
                                    <div class="cell action">Payment Advanced Cash</div>
                                    <div class="cell object">1400.03</div>
                                    <div class="cell desc"></div>
                                </div>
                                <div class="row table">
                                    <div class="cell date">2018-08-26 06:35:29</div>
                                    <div class="cell summ">-700.00</div>
                                    <div class="cell action">Перевод средств АА26004</div>
                                    <div class="cell object">700.00</div>
                                    <div class="cell desc"></div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination">
                            <div class="page active">1</div>
                            <div class="page">2</div>
                            <div class="page">3</div>
                        </div>
                        <div class="flex space-between export-show">
                            <div class="flex vert-center">
                                <span>Экспорт выписки</span>
                                <input type="button" class="exel">
                                <input type="button" class="pdf">
                            </div>
                            <div class="flex vert-center">
                                <span>Показывать по</span>
                                <select name="" id="" class="green">
                                    <option value="">5</option>
                                    <option value="">10</option>
                                    <option value="">20</option>
                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="heading">Маркет</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                        <div class="tab">Личный кабинет</div>
                        <div class="tab">LC shop</div>
                        <div class="tab">LC Landing</div>
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont">
                    <div class="padd-block stars">
                        <div class="text-block">
                            В личном кабинете находятся все инструменты, необходимые для работы с сайтом. <br>
                            Личный кабинет доступен зарегистрированным пользователям, после авторизации на сайте.
                            <br><br>
                            Личный кабинет содержит ряд пунктов для упрощения и улучшения работы в компании:
                            <br><br>
                            Мой профиль <br>
                            - Личная информация указанная при регистрации, стройки приватности профиля. <br>
                            Мой кошелёк <br>
                            - Баланс вашего счета . Перевод средств между участниками <br>
                            Моя группа. <br>
                            - Ваше положение в Бизнес-группе <br>
                            Мониторинг <br>
                            - Личные данные реферальных партнеров и их местонахождение в бизнес-группе. <br>
                            Обратная связь <br>
                            - Возможность общения с участниками проекта. Решение технических вопросов по проекту. <br>
                            Выйти <br>
                            - Выход из личного кабинета
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block stars">
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block stars">
                        <div class="img-block">
                            <?php echo Html::img('@web/img/landing.jpg') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
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
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="params-block">
                            <div class="string">
                                <div class="label">Логин</div>
                                <input type="text">
                            </div>
                            <div class="string">
                                <div class="label">ФИО</div>
                                <input type="text">
                            </div>
                            <div class="string">
                                <div class="label">Спонсор</div>
                                <input type="text">
                            </div>
                            <div class="string">
                                <div class="label">Телефон</div>
                                <input type="text">
                            </div>
                            <div class="string">
                                <div class="label">Статаус</div>
                                <select name="" id="">
                                    <option value="">Все пользователи</option>
                                    <option value="">Активные пользователи</option>
                                    <option value="">Не активные пользователи</option>
                                </select>
                            </div>
                            <input type="button" class="button big" value="Поиск">
                        </div>
                        <div class="scroll-block">
                            <div class="table-block">
                                <div class="row heading v2">
                                    <div class="cell date reg up">Дата <br> регистрации <span class="up">&#8593;</span><span class="down">&#8595;</span></div>
                                    <div class="cell login">Логин</div>
                                    <div class="cell name">ФИО</div>
                                    <div class="cell mail">E-mail</div>
                                    <div class="cell phone">Телефон</div>
                                    <div class="cell skype">Skype</div>
                                    <div class="cell refs">Рефералов</div>
                                </div>
                                <div class="row v2 clicked">
                                    <div class="cell date reg up">2018-08-26 </div>
                                    <div class="cell login">АА26004</div>
                                    <div class="cell name">Шишкина Маша Михайловна</div>
                                    <div class="cell mail">anatol.avramenko@yandex.ru</div>
                                    <div class="cell phone">7929367560</div>
                                    <div class="cell skype">anavr07</div>
                                    <div class="cell refs">0</div>
                                </div>
                                <div class="row hidden">
                                    <div class="title">Спонсорская линия</div>
                                    <div class="string">1 Линия: <span class="red">AA42726</span> Avramenko Anatolii Aleksandrovich</div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination">
                            <div class="page active">1</div>
                            <div class="page">2</div>
                            <div class="page">3</div>
                        </div>
                        <div class="flex space-between export-show">
                            <div class="flex vert-center">
                                <span>Экспорт выписки</span>
                                <input type="button" class="exel">
                                <input type="button" class="pdf">
                            </div>
                            <div class="flex vert-center">
                                <span>Показывать по</span>
                                <select name="" id="" class="green">
                                    <option value="">5</option>
                                    <option value="">10</option>
                                    <option value="">20</option>
                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
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
                    </div>
                    <div class="padd-block">
                        <div class="cycle-block">
                            <div class="string flex centered vert-start wrap">
                                <div class="cycle">
                                    <div class="title">ПРЕДСТАРТ !</div>
                                    <div class="summ">0</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">ПРЕДСТАРТ 2</div>
                                    <div class="summ">0</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">1 Цикл</div>
                                    <div class="summ">НП</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">ПЕРЕХОДНОЙ ЦИКЛ</div>
                                    <div class="summ">0</div>
                                </div>
                            </div>
                            <div class="string flex centered vert-start wrap">
                                <div class="cycle">
                                    <div class="title">2 ЦИКЛ</div>
                                    <div class="summ">НП</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">ПЕРЕХОДНОЙ ЦИКЛ</div>
                                    <div class="summ">0</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">3 ЦИКЛ</div>
                                    <div class="summ">НП</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">4 ЦИКЛ</div>
                                    <div class="summ">НП</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">ПЕРЕХОДНОЙ ЦИКЛ</div>
                                    <div class="summ">0</div>
                                </div>
                                <div class="cycle">
                                    <div class="title">5 ЦИКЛ</div>
                                    <div class="summ">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="padd-block">
                        <div class="flex centered comand">
                            <div class="flex column centered">
                                <div class="block-logo">
                                    <div class="user-logo" style="background-image: url('../img/user.png')"></div>
                                </div>
                                <div class="data">
                                    <div class="ident">aa42726</div>
                                    <div class="name">Мишкина Маша Михайловна</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex centered comand wrap">
                            <div class="block-logo"></div>
                            <div class="block-logo"></div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
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
                    </div>
                    <div class="padd-block">
                        <div class="profile-block">
                            <div class="string">
                                <div class="folder"></div>
                                <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                <div class="search"></div>
                            </div>
                            <div class="profile-block mini">
                                <div class="string">
                                    <div class="folder"></div>
                                    <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                    <div class="search"></div>
                                </div>
                            </div>
                            <div class="profile-block mini">
                                <div class="string">
                                    <div class="folder"></div>
                                    <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                    <div class="search"></div>
                                </div>
                                <div class="profile-block mini">
                                    <div class="string">
                                        <div class="folder"></div>
                                        <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                        <div class="search"></div>
                                    </div>
                                    <div class="profile-block mini">
                                        <div class="string">
                                            <div class="folder"></div>
                                            <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                            <div class="search"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-block mini">
                            <div class="string">
                                <div class="folder"></div>
                                <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                <div class="search"></div>
                            </div>
                        </div>
                        <div class="profile-block mini">
                            <div class="string">
                                <div class="folder"></div>
                                <div class="name">AA26004 Avramenko Anatolii Aleksandrovich </div>
                                <div class="search"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="heading">Новости</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="news-block">
                            <div class="title">Lorem ipsum</div>
                            <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            <a href="#" class="read-more">Читать далее...</a>
                        </div>
                        <div class="news-block">
                            <div class="title">Lorem ipsum</div>
                            <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            <a href="#" class="read-more">Читать далее...</a>
                        </div>
                        <div class="news-block">
                            <div class="title">Lorem ipsum</div>
                            <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            <a href="#" class="read-more">Читать далее...</a>
                        </div>
                        <div class="news-block">
                            <div class="title">Lorem ipsum</div>
                            <div class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            <a href="#" class="read-more">Читать далее...</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="heading">Обратная связь</div>
                <div class="tabs secondary">
                    <div class="tab-block">
                        <div class="tab">Отправить</div>
                        <div class="tab">Входящие</div>
                        <div class="tab">Исходящие</div>
                        <div class="tab">Оставить отзыв</div>
                    </div>
                </div>
            </div>
            <div class="tab-content secondary">
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block flex-start message">
                            <div class="input-log">
                                <div class="string">
                                    <span>От кого <sapn class="star">*</sapn></span>
                                    <input type="text" class="disabled" value="AA42726">
                                </div>
                                <div class="string">
                                    <div class="checkbox for-user">Кому <div>(Логин/e-mail)</div></div>
                                    <input type="text" class="disabled">
                                </div>
                                <div class="string">
                                    <span class="checkbox active support">Теххподдержка</span>
                                </div>
                                <div class="string theme">
                                    <span>Тема <span class="star">*</span></span>
                                    <select class="default support">
                                        <option value="">Тема</option>
                                        <option value="">Вывод средств</option>
                                        <option value="">Не приходит код подтверждения</option>
                                        <option value="">Изменение личных данных</option>
                                        <option value="">Смена спонсора</option>
                                        <option value="">Запрет редактирования данных</option>
                                        <option value="">Заказ лендинга</option>
                                        <option value="">Другое</option>
                                    </select>
                                    <input type="text" class="hiden support">
                                </div>
                                <div class="string vert-start">
                                    <span>Сообщение <span class="star">*</span></span>
                                    <textarea name="" id=""></textarea>
                                </div>
                                <div class="string flex-end">
                                    <input type="button" class="button big" value="Отправить">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="scroll-block">
                            <div class="table-block">
                                <div class="row heading">
                                    <div class="cell empty"></div>
                                    <div class="cell date up">Дата <span class="up">&#8593;</span><span class="down">&#8595;</span></div>
                                    <div class="cell">От кого</div>
                                    <div class="cell">E-mail</div>
                                    <div class="cell">Тема</div>
                                    <div class="cell">Диалог</div>
                                </div>
                                <div class="row">
                                    <div class="cell message">Нет результатов</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-between">
                            <div class="flex vert-center">
                            </div>
                            <div class="flex vert-center">
                                <span>Показывать по</span>
                                <select name="" id="" class="green">
                                    <option value="">5</option>
                                    <option value="">10</option>
                                    <option value="">20</option>
                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="scroll-block">
                            <div class="table-block">
                                <div class="row heading">
                                    <div class="cell date up">Дата <span class="up">&#8593;</span><span class="down">&#8595;</span></div>
                                    <div class="cell">От кого</div>
                                    <div class="cell">E-mail</div>
                                    <div class="cell">Тема</div>
                                    <div class="cell">Диалог</div>
                                </div>
                                <div class="row">
                                    <div class="cell message">Нет результатов</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-between">
                            <div class="flex vert-center">
                            </div>
                            <div class="flex vert-center">
                                <span>Показывать по</span>
                                <select name="" id="" class="green">
                                    <option value="">5</option>
                                    <option value="">10</option>
                                    <option value="">20</option>
                                    <option value="">40</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="tab-cont">
                    <div class="padd-block">
                        <div class="popup-block flex-start">
                            <div class="input-log">
                                <div class="string">
                                    <span>Изображение <sapn class="star">*</sapn></span>
                                    <input type="file">
                                </div>
                                <div class="string">
                                    <span>Отзыв <sapn class="star">*</sapn></span>
                                    <input type="text">
                                </div>
                                <div class="string flex-end">
                                    <input type="button" class="button big" value="Отправить">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php $this->registerJsFile('@web/js/jquery.mask.js',['depends' => 'yii\web\YiiAsset']); ?>
<?php
$js = <<<JS
    $('#registerform-datebirth').mask('00.00.0000');

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