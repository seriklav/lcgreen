<?php
$this->title = 'Ежедневные Деньги(EUR)';

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\models\StepUsersEur;
use app\models\Step;
use app\components\Tree;

/**
 * @var $clones \app\models\UserClone[]|[]
 * @var $dailyes \app\models\DailyMoneyEur[]
 * @var $dataClones \app\models\UserClone[]
 */
?>
    <style>

        .grid-view th, .grid-view th a {
            background: #53a510;
            color: #fff;
        }
    </style>
    <div class="tab-cont active">
        <div class="padd-block">
            <div class="heading"><?= $this->title ?></div>
            <div class="tabs secondary">
                <div class="tab-block">
                    <!--                <a href="#tab1" class="tab ">Параметры</a>-->
                    <a href="#tab2" class="tab active">Площадки</a>
                    <a href="#tab3" class="tab">Места в программе "<?= $this->title ?>"</a>
                    <?php if($dataClones): ?>
                        <?= \yii\helpers\Html::dropDownList('clones',
                            $activeClone? $activeClone->id: '',
                            $dataClones,
                            ['prompt' => 'выберите аккаунты', 'class' => 'tab', 'data-user' => Yii::$app->user->identity->id]) ?>
                    <?php endif;?>
                </div>
            </div>
        </div>


        <div class="tab-content secondary ">
            <div class="padd-block text-center">
                <?php if (Yii::$app->session->hasFlash('message')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <?php echo Yii::$app->session->getFlash('message'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="tab2" class="tab-cont active">
                <?= $this->render('./daily-money-eur/tab-2', [
                    'model' => $model,
                    'dailyes' => $dailyes,
                    'clones' => $clones,
                    'step_name' => $step_name,
                    'step_price' => $step_price,
                    'step_place' => $step_place,
                    'users_step' => $users_step,
                    'out' => $out,
                    'out_user' => $out_user
                ]) ?>
            </div>
            <div id="tab3" class="tab-cont">
                <?= $this->render('./daily-money-eur/tab-3', [
                    'model' => $model,
                    'dailyes' => $dailyes
                ]) ?>
            </div>
        </div>
    </div>


<?php

$this->registerJsFile('@web/js/jquery-ui.min.js', ['depends' => 'yii\web\YiiAsset']);
$this->registerCssFile('@web/css/jquery-ui.min.css', ['depends' => 'yii\web\YiiAsset']);

$url = URL::to(['/user/daily-money-eur']);
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
$('body').on('change', 'select[name="clones"]', function(e) {
    var clone = $(this).val();
    if(!clone) {
          window.location.reload();
    }
    $.ajax({
        url: "$url",
        data: {
            clone: clone,
            user: $(this).data('user')            
        },
        type: 'POST',
        success :function(request) {
             window.location.reload();  
        }
    });
});
$('body').on('click', '.close-fields', function(event ) {
      var clone = $(this).data('clone');
    $(this).addClass('show-fields');
    $(this).removeClass('close-fields')
    $(this).text('показать данные');
    $('out #out-'+clone).remove();
    return false;
    
});

$('body').on('click', '.open-clone', function(event ) {
    event.preventDefault();
    var This = $(this);
    var clone = $(this).data('clone');
       if(!clone) {
         return false;
    }
      $.ajax({
        url: "$url",
        data: {
            clone: clone,
            user: $(this).data('user')            
        },
        type: 'POST',
        success :function(request) {
            if(request == 1) {
                window.location.reload();
            }else {
                $('out').append(request)
            }         
        }
    });
});
$('body').on('click', '.show-fields', function(event ) {
    event.preventDefault();
    var clone = $(this).data('clone');
    if(  $('out #out-'+clone).length) {
       $("html, body").animate({ scrollTop:   $('out #out-'+clone).position().top }, 1000);  
       return false;
   }
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
    
    $('body').on('click', '.tab-content #step1 .hex, .tab-content #step1 .cycle',  function(e) {
       if($( this ).attr('data-have') == true) 
        {
            if(!$(this).data('cycle')) {
                return false;
            }
          $.ajax({
              url: "$url",
              data: {number: $(this).data('cycle')},
              type: 'POST',
                  success :function(mess) 
                  {
                      location.href = "#tab2";
                      location.reload();
                  },
              }
          );
                return;
        }
    }); 
    $('.tab-content #program-daily-money .hex, .tab-content #program-daily-money .cycle').on('click',function() {
              if(!$(this).data('cycle')) {
                  if($(this).data('clone')) {
                      $('#clone-'+$(this).data('clone')).submit()
                  }
                return false;
            }
       $( "#number_step" ).val($(this).data('cycle'));
        dialog.dialog( "open" );   
    });
    if(location.href.indexOf('#tab1') > -1) {
        $('.tabs.secondary a[href="#tab1"]').trigger('click');
    }  
    if(location.href.indexOf('#tab2') > -1) {    
        $('.tabs.secondary a[href="#tab2"]').trigger('click');
    }    
    if(location.href.indexOf('#tab3') > -1) {    
        $('.tabs.secondary a[href="#tab3"]').trigger('click');
    }  
JS;
$this->registerJs($js);

if (Yii::$app->user->identity->role == 1) {

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
}
