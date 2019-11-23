<?php

use app\models\Step;
use app\models\StepUsers;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * @var $dailyes \app\models\DailyMoney[]* @var $out_user array
 * @var $allClonesStep Step[]|[]
 * @var $out Step|[]
 * @var $clones \app\models\Step[]|[]
 * @var $all array
 * @var $step_place array
 * @var $out_user Step[]
 */
?>

<div class="padd-block " id="step1">
    <div class="row">
        <div class="col-md-10 col-lg-10 col-sm-9">
            <?php $stepCount = 5;
            for ($i = 0; $i < count($dailyes);): ?>
                <div class="string flex centered vert-start wrap">
                    <?php
                    $steps = [];
                    if($activeClone = Yii::$app->user->identity->activeClone) {
                        $steps = $activeClone->getSteps()->indexBy('step')->column();
                    }
                    foreach (array_slice($dailyes, $i, $stepCount) as $daily):
                        $have = false;
                        if ($num = ArrayHelper::getValue($steps, $daily->id)) {
                            $have = true;
                        }
                        $active = '';
                        if( Yii::$app->user->identity->daily_step == $daily->id && $num) {
                            $active = 'active';
                        }
                        ?>
                        <div class="cycle <?= $active ?>" data-cycle="<?= $daily->id; ?>" data-have="<?= $have ?>">
                            <div class="title"><?= $daily->name; ?></div>
                            <?php if(!  $have): ?>
                            <div class="summ"><?= $daily->price; ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php $i = $stepCount;
                $stepCount += 5; endfor; ?>
        </div>
        <?php if ($clones): ?>
        <div class="col-md-2 col-lg-2 col-sm-3 col-xs-12 center">
            <clones style="max-width: 200px">
                <div class="text-uppercase">Мой клоны</div>
                <div class="string">
                    <?php foreach ($clones as $step):
                        $active = false;
                        $user = Yii::$app->user->identity;
                        if($user->activeClone->id == $step->clone) {
                            $active = 'active';
                        }
                        $n = str_replace($user->username, '', $step->cloneOne->name);
                        ?>
                        <button data-clone="<?= $active? '': $step->clone ?>" class="<?= $active ?> open-clone title button btn btn-block" style="margin-top: 10px"><?= $n ?>  клон</button>
                    <?php endforeach; ?>
                </div>
            </clones>
        </div>

        <?php endif; ?>
    </div>

    <div class="exit-user flex vert-start wrap hide">
        <?php
        $showBtn = false;
        $maxStep = \app\models\DailyMoney::getMaxDaily();
        foreach ($out_user as $step):
            if($clone = $step->cloneOne):
                $user = $clone->user;
                if($clone->name != $user->username && $step->step == $maxStep->id) {
                    continue;
                }
                $showBtn = true;
                if($step->GetStepsUsers()->count() != $step->daily->place) {
                    $step->status = Step::STATUS_PROCESSING;
                    $step->update();
                    continue;
                } elseif($step->status != Step::STATUS_CLOSURES) {
                    $step->status = Step::STATUS_CLOSURES;
                    $step->update();
                }
                $active = '';
                if($user->activeClone && $user->activeClone->id == $clone->id) {
                    $active = 'active';
                }
            ?>
                <div class="comand">
                    <div class="block-logo" style="width: 100px; min-height: 100px;">
                        <div class="user-logo"
                             style="width:90px;height auto; background-image: url('<?php if (!$user->avatar) echo '../../web/img/user.png';
                             else echo '../../web/' . $user->avatar; ?>')"></div>
                    </div>
                    <div class="data">
                        <div class="ident"> <?= $clone->name; ?> </div>
                        <div class="name"> <?= "$user->name  $user->surname    $user->father";?> </div>
                    </div>
                    <button data-clone="<?=$clone->id?>" class="open-clone button btn btn-block <?= $active?>"  style="margin-top: 10px; max-width: 160px;">показать данные</button>
                </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php
    if ($showBtn): ?>
        <button type="button" id="load_exit_user" class="btn" style="background: #df8545; color:#fff;">Вышедшие аккаунты</button>
    <?php endif; ?>
</div>
<out class=" padd-block centered">
    <?= $this->render('out', compact('out', 'step_place'))?>
</out>

