
<?php

use app\models\Step;
use yii\helpers\ArrayHelper;

/**
 * @var $dailyes \app\models\DailyMoney[]
 * @var $mon_step integer
 * @var $out_user array
 * @var $allClonesStep Step[]|[]
 * @var $out Step|[]
 * @var $clones \app\models\Step[]|[]
 * @var $step_place array
 */
$myUser = Yii::$app->user->identity;
if ($out && $out->cloneOne):
    if( $out->status == Step::STATUS_PROCESSING):
            if(!$user = $out->cloneOne->user) {
//                return '';
            };
        $outSteps = Step::find()->where(['sponsor' => $out->cloneOne->name, 'step' => $out->step])->andWhere(['!=', 'id', $out->id]) ->andWhere(['not', ['clone' => null]])->all();
        $n = str_replace($user->username, '', $out->cloneOne->name);
        ?>
            <div class="flex centered comand">
                <div class="flex column centered">
                    <div class="block-logo">
                        <div class="user-logo"
                             style="background-image: url('<?php if (!$user->avatar) echo '../../web/img/user.png';
                             else echo '../../web/' . $user->avatar; ?>')"></div>
                    </div>
                    <div class="data">
                        <div class="ident"><?= $out->cloneOne->name; ?></div>
                        <div class="name"><?= "$user->name {$n} $user->surname $user->father";?> </div>
                    </div>
                </div>
            </div>
            <div class="flex centered comand subs ">
                <?php
                $loop = ArrayHelper::getValue($step_place, $user->daily_step, 2);
                for ($i = 0; $i < $loop; $i++):
                    ?>
                    <div class="block-logo">
                        <?php
                        /**@var  $stepOne Step */

                        if($stepOne = ArrayHelper::getValue($outSteps, $i)):
                            $n = '';
                            if($outClone = $stepOne->cloneOne):
                                $user =  $outClone->user;
                                $n = str_replace($user->username, '', $outClone->name);
                               ?>
                                   <div class="user-logo"
                                        style="background-image: url('<?php if (!$user->avatar) echo '../../web/img/user.png';
                                        else echo '../../web/' . $user->avatar; ?>')"></div>
                                   <div class="data">
                                       <div class="ident"> <?= $outClone->name; ?> </div>
                                       <div class="name"><?= "$user->name {$n} $user->surname $user->father";?> </div>
                                   </div>
                                   <button data-clone="<?=$outClone->id?>" data-sponsor="<?= $outClone->sponsor?>" data-user="<?= $user->id ?>"  class="show-fields button btn btn-block" style="margin-top: 10px">показать данные</button>

                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>


            </div>
    <?php endif;
    /** @var $myUser \app\models\User  */
    $ids = \app\models\User::sponsor()->getReferals($myUser->username)->select(['id'])->column();
    foreach ($out->cloneOne->GetCloneSubs()->andWhere(['user_id' => $ids])->all() as $outClone):

        $user = $outClone->user;
        $data = $outClone->getStepOne()->one();
        $n = str_replace($user->username, '', $outClone->name);
        echo  $this->render('./out-data', ['out' => $data, 'step_place' => $step_place, 'notAjax' => true]);
    endforeach;
    endif;

