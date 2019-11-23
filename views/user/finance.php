<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;

$this->title = 'Информация по счету';
?>
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
        <div class="tab-cont active">
            <div class="padd-block balance">
                <div class="table-block balance">
                    <div class="row table">
                        <div class="heading">Баланс(LC)</div>
                        <div class="cell"><?php echo Yii::$app->user->identity->lc; ?> LC</div>
                    </div>
                    <div class="row table">
                        <div class="heading">Баланс(EUR)</div>
                        <div class="cell"><?php echo Yii::$app->user->identity->eur; ?> EUR</div>
                    </div>
                    <div class="row table">
                        <div class="heading" style="display: flex; justify-content: space-evenly;">Заблокированно средств (LC)
                            <?php if(Yii::$app->user->identity->clones): ?>
                                <i data-toggle="modal" data-target="#freeze-history" class="material-icons" style="cursor: pointer; float: right;">history</i>
                            <?php endif; ?>
                        </div>
                        <div class="cell"><?php echo Yii::$app->user->identity->freeze; ?> LC </div>
                    </div>
                    <div class="row table">
                        <div class="heading" style="display: flex; justify-content: space-evenly;">Заблокированно средств (EUR)
                            <?php if(Yii::$app->user->identity->clones_eur): ?>
                                <i data-toggle="modal" data-target="#freeze-history" class="material-icons" style="cursor: pointer; float: right;">history</i>
                            <?php endif; ?>
                        </div>
                        <div class="cell"><?php echo Yii::$app->user->identity->freeze_eur; ?> EUR </div>
                    </div>
                    <div class="row table">
                        <div class="heading">Уровень</div>
                        <div class="cell"><?php echo Yii::$app->user->identity->step; ?></div>
                    </div>
                    <div class="row table">
                        <div class="heading">Рефералов</div>
                        <div class="cell"><?php echo $ref; ?></div>
                    </div>
                    <div class="row table">
                        <div class="heading">Структура</div>
                        <div class="cell"><?php echo $strukt; ?></div>
                    </div>
                    <div class="row table">
                        <div class="heading">Дней в проекте</div>
                        <?php
                            $date = new DateTime(Yii::$app->user->identity->dateReg);
                            $now = new DateTime();
                            $diff = $now->diff($date, true);
                            $days = $diff->days;
                        ?>
                        <div class="cell"><?php echo $days; ?></div>
                    </div>
                </div>
            </div>
            <?php
                Modal::begin([
                    'header' => false,
                    'id' => 'freeze-history',
                    'size' => 'md',
                    'bodyOptions' => ['class' => 'table-block balance modal-body']
                ]);
            ?>
            <div>
                <?php foreach (Yii::$app->user->identity->clones as $clone): ?>
                    <div class="row table">
                        <div class="heading"><?=  $clone->name ?></div>
                        <div class="cell"><?= $clone->freeze; ?>  LC</div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php Modal::end();?>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <?php if(Yii::$app->session->hasFlash('transfer')): ?>
                    <div class="alert alert-danger alert-dismissible show" role="alert">
                        <?php echo Yii::$app->session->getFlash('transfer'); ?>
                    </div>
                <?php endif; ?>
                <div class="popup-block sides">
                    <div class="l-col">
                        <?php $form = ActiveForm::begin(['id' => 'transfer']); ?>
                        <div class="label">От пользователя</div>
                        <select name="" id="">
                            <option value=""><?php echo Yii::$app->user->identity->username; ?></option>
                        </select>

                        <div class="label">Получатель</div>
                        <?= $form->field($transfer, 'userto')->textInput()->label(false) ?>
                        
                        <div class="label">Сумма <span class="star">*</span> </div>
                        <?= $form->field($transfer, 'sum')->textInput()->label(false) ?>


	                    <?= $form->field($transfer, 'currency')
		                    ->dropdownList(['LC' => 'LC','EUR' => 'EUR'])
		                    ->label(true) ?>

                        <div class="label">E-mail код</div>
                        <?= $form->field($transfer, 'emailCode')->textInput()->label(false) ?>
                        <div class="button-block">
                            <input id="send_code" type="button" class="button" value="Получить код">
                        </div>
                        <input type="submit" class="button big" name="transfer_button" value="Отправить">
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="r-col">
                        <div class="info">Внутренние переводы участникам проекта происходят мгновенно и не облагаются комиссией.</div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .field-userwithdraw-wallet{
                max-width: 332px;
                width: 100%;
            }
        </style>
        <div class="tab-cont">
            <div class="padd-block">
                <?php if(Yii::$app->session->hasFlash('message')): ?>
                    <div class="alert alert-danger alert-dismissible show" role="alert">
                        <?php echo Yii::$app->session->getFlash('message'); ?>
                    </div>
                <?php endif; ?>
                <div class="popup-block">
                    <div class="text-block">Сумма для вывода должна быть кратна 100 и вводится без учета комиссии. <br> Комиссия за вывод средств составляет 5%</div>
                    <div class="text-block">Вывод средств на любые платежные системы может занимать 1-15 рабочих дней. <br> Средства с кабинета списываются автоматически.</div>
                    <?php $form = ActiveForm::begin(['id' => 'withdraw',
                        'options' => ['class' => 'text-block'] ]
                    ); ?>
                    <div class="string v2">
                        <span>Сумма <span class="star">*</span></span>
                        <div class="summ">
                            <?= $form->field($model, 'sum')->textInput()->label(false) ?>
                        </div>
	                    <?= $form->field($model, 'currency')
		                    ->dropdownList(['LC' => 'LC','EUR' => 'EUR'])
		                    ->label(false) ?>
                    </div>
                    <div class="string v2 padd-right">
                        <span>Способ вывода <span class="star">*</span></span>
                        <?= $form->field($model, 'type')
                            ->dropdownList(['Perfect Money','Payeer','Qiwi'])
                            ->label(false) ?>
                    </div>
                    <div class="string v2 padd-right">
                        <span>НОМЕР СЧЕТА <span class="star">*</span></span>
                        <?= $form->field($model, 'wallet')->textInput()->label(false) ?>
                    </div>
                    <?= Html::submitButton('Отправить', ['class' => 'button big', 'name' => 'send-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="popup-block">
                    <div class="text-block bold" style="margin-bottom: 10px;">
                        Текущий курс: 1 USD = <?php echo $setting->kurs; ?>
                    </div>
                    <div class="text-block bold">
                        Текущий курс: 1 EUR = <?php echo $setting->kurs_eur; ?>
                    </div>
                    <input type="hidden" id="kurs" value="<?php echo $setting->kurs; ?>">
                    <input type="hidden" id="kurs_eur" value="<?php echo $setting->kurs_eur; ?>">
	                <input type="hidden" id="perfect_usd" value="<?php echo $setting->perfect; ?>">
                    <input type="hidden" id="perfect_eur" value="<?php echo $setting->perfect_eur; ?>">
                    <div class="text-block">Платеж будет осуществлен через систему Perfect Money. <br> Сумма к оплате указывается без учета коммиссии</div>
                    <form action="https://perfectmoney.is/api/step1.asp" method="POST" style="max-width: 550px; width: 100%;">
                        <input type="hidden" name="PAYEE_ACCOUNT" id="PAYEE_ACCOUNT" value="<?php echo $setting->perfect; ?>">
                        <input type="hidden" name="PAYEE_NAME" value="Greenlife">
<!--                        <input type="hidden" name="PAYMENT_UNITS" value="USD">-->
                        <input type="hidden" name="PAYMENT_AMOUNT" id="oplata_payment">

                        <input type="hidden" name="STATUS_URL" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/perfect/perfect']); ?>">
                        <input type="hidden" name="PAYMENT_URL" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/perfect/succes']); ?>">
                        <input type="hidden" name="NOPAYMENT_URL" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl(['/perfect/succes']); ?>">

                        <input type="hidden" name="PAYMENT_ID" value="<?php echo Yii::$app->user->identity->username; ?>">

                        <div class="string v2">
                            <span>Сумма внесения <span class="star">*</span></span>
                            <div class="summ">
                                <input type="text" id="oplata_amount">
                                <span>LC</span>
                            </div>
                        </div>
                        <div class="string v2">
                            <span>К оплате</span>
                            <div class="summ" id="oplata">
                                <span>0</span>

	                            <select name="PAYMENT_UNITS" id="PAYMENT_UNITS" style="width: 70px;">
		                            <option value="USD" selected>USD</option>
		                            <option value="EUR">EUR</option>
	                            </select>
                            </div>
                        </div>
                        <div class="string v2 padd-right">
                            <span>Платежный сервис</span>
                            <select name="" id="">
                                <option value="">Perfect Money</option>
                            </select>
                        </div>
                        <input type="submit" name="PAYMENT_METHOD" class="button big" value="Отправить">
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-cont">
            <div class="padd-block">
                <div class="scroll-block">
                    <?php
                    Pjax::begin(['id' => 'countries']);
                    $searchModel = new \app\models\search\UserLogSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['label' => '#', 'attribute' => 'id'],
                            ['label' => 'Дата', 'attribute' => 'date'],
                            ['label' => 'Сумма', 'attribute' => 'sum'],
                            ['label' => 'Детали операции', 'attribute' => 'action'],
                        ]
                    ]);
                    Pjax::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$url = URL::to(['/user/finance']);
$js = <<<JS

    $('#oplata_amount').keyup(function() {
		changeAmount();
    });
    $('#PAYMENT_UNITS').change(function() {
		changeAmount();
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
    
    function changeAmount() {
        var currency = $('#PAYMENT_UNITS').val();
        var kurs = 0;
        var perfect = 0;
        
        switch (currency) {
          case 'EUR':
              kurs = $('#kurs_eur').val();
              perfect = $('#perfect_eur').val();
              break;
          default:
              kurs = $('#kurs').val();
              perfect = $('#perfect_usd').val();
              break;
        }
        
        if( $('#oplata_amount').val() < 0 || !$.isNumeric( $('#oplata_amount').val() )) return;
       $('#oplata > span').html( ($('#oplata_amount').val() / kurs).toFixed(1));
       $('#oplata_payment').val(($('#oplata_amount').val() / kurs).toFixed(1));
       $('#PAYEE_ACCOUNT').val(perfect);
    }
JS;
$this->registerJs($js);
?>