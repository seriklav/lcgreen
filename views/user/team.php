<?php
$this->title = 'Бизнес-группа';
use yii\helpers\Url;
use app\models\User;
use app\models\Step;
use app\models\StepUsers;
?>
<style>
    .tab-content.secondary .cycle{
        cursor: pointer;
    }
</style>
<div class="tab-cont active">
    <div class="padd-block">
        <div class="heading">Бизнес-группа</div>
        <div class="tabs secondary">
            <div class="tab-block"></div>
        </div>
    </div>
    <div class="tab-content secondary">
        <div class="tab-cont active">
      <div class="padd-block ">
        <div class="flex centered comand wrap">
            <div class="title"><?php echo $step_name;?></div>
        </div>
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
            <?php $loop = $step_place[Yii::$app->user->identity->step] - 1;
            $seven = array();
            if($step_place[Yii::$app->user->identity->step] == 7) $loop = 2;  ?>
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
            for($i = $loop; $i < $step_place[Yii::$app->user->identity->step] - 1; $i++){ ?>
                <div class="block-logo">
                    <?php
                    if(($i == 2 || $i == 3) && @$seven[0])
                    {
                        $minus = 2;
                        $seven_step = Step::findOne([
                            'owner_id' => $seven[0],
                            'status' => 0,
                            'step' =>  Yii::$app->user->identity->step
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
                            'step' =>  Yii::$app->user->identity->step
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
        </div>
    </div>
</div>
<?php
$url = URL::to(['/user/team']);
$js = <<<JS
    $('.tab-content.secondary .cycle').on('click',function(){
          $.ajax({
          url: "$url",
          data: {number: $(this).index()},
          type: 'POST',
              success :function(mess) 
              {
                  if(mess) return alert(mess);
                  location.reload();
              },
          }
      );
    });
JS;
$this->registerJs($js);?>