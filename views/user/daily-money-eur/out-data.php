
<?php

use app\models\Step;
use app\models\StepEur;
use yii\helpers\ArrayHelper;

/**
 * @var $dailyes \app\models\DailyMoneyEur[]
 * @var $mon_step integer
 * @var $out_user array
 * @var $allClonesStep StepEur[]|[]
 * @var $out StepEur|[]
 * @var $clones StepEur[]|[]
 * @var $step_place array
 */
/** @var bool $notAjax */
if(!isset($notAjax)) {
    $notAjax = false;
}
$myUser = Yii::$app->user->identity;
if ($out && $out->cloneOne):
if ( $out->status == StepEur::STATUS_PROCESSING):
    $user = $out->cloneOne->user;
    $outSteps = StepEur::find()->where(['sponsor' => $out->cloneOne->name, 'step' => $out->step])->andWhere(['!=', 'id', $out->id]) ->andWhere(['not', ['clone' => null]])->all();
    $n = str_replace($user->username, '', $out->cloneOne->name);
    ?>
<div id="out-<?= $out->cloneOne->id?>">
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
        $loop = ArrayHelper::getValue($step_place, $myUser->daily_step_eur, 2);
        for ($i = 0; $i < $loop; $i++):
            ?>
            <div class="block-logo">
                <?php
                /**@var  $stepOne Step */
                if($stepOne = ArrayHelper::getValue($outSteps, $i)):
                    if($outClone = $stepOne->cloneOne):

                    $user = $outClone->user;
                    $n = str_replace($user->username, '', $outClone->name);
                    ?>
                    <div class="user-logo"
                         style="background-image: url('<?php if (!$user->avatar) echo '../../web/img/user.png';
                         else echo '../../web/' . $user->avatar; ?>')"></div>
                    <div class="data">
                        <div class="ident"> <?php echo $outClone->name; ?> </div>
                        <div class="name"><?= "$user->name {$n} $user->surname $user->father";?> </div>
                    </div>
                    <button data-clone="<?=$outClone->id?>" data-sponsor="<?= $outClone->sponsor?>" data-user="<?= $user->id ?>"  class="show-fields button btn btn-block" style="margin-top: 10px">показать данные</button>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<?php endif;
if($notAjax) {
    /** @var $myUser \app\models\User  */
    $ids = \app\models\User::sponsorEur()->getReferals($myUser->username)->select(['id'])->column();
    foreach ($out->cloneOne->GetCloneSubs()->andWhere(['user_id' => $ids])->all() as $outClone):
        $user = $outClone->user;
        $data = $outClone->getStepOne()->one();
        $n = str_replace($user->username, '', $outClone->name);
        echo  $this->render('./out-data', ['out' => $data, 'step_place' => $step_place, 'notAjax' => true]);
    endforeach;
}
endif;

