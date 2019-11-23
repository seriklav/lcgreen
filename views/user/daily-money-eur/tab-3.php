<?php
/**
 *@var $dailyes \app\models\DailyMoneyEur
 */

use app\models\StepEur;
use yii\bootstrap\ActiveForm;
$clone = new \app\models\UserCloneEur();
$clone->user_id = Yii::$app->user->id;
$maxStep  =  \app\models\DailyMoneyEur::getMaxDaily();

?>
<div class="padd-block" id="program-daily-money">
    <div class="cycle-block">
        <?php
        /**@var $activeClone \app\models\UserCloneEur */
        /**@var $user \app\models\User */
        $user = Yii::$app->user->identity;
        $activeClone = $user->activeCloneEur;

        foreach ($dailyes as $daily):
            if($daily->category == \app\models\DailyMoneyEur::CATEGORY_BONUS) {
                if(!$activeClone || !$activeClone->getStepOne($maxStep->id)->one()) {
                    continue;
                }
            }
            $step = false;
            if($activeClone) {
                $step = $activeClone->getStepOne($daily->id)->one();
            }

            if($step && $daily->id == $step->step) :
                if($step->status == StepEur::STATUS_PROCESSING):
                    if($daily->category != \app\models\DailyMoneyEur::CATEGORY_BONUS) :
                        $clone->step = $step->id;
                        $clone->number = $daily->id;
                        $clone->sponsor = $step->cloneOne->name;
                        ?>
                        <?php $form = ActiveForm::begin(['id' => 'clone-'.$daily->id]) ?>
                        <?= $form->field($clone, 'number') ->hiddenInput() ->label(false) ?>
                        <?= $form->field($clone, 'sponsor') ->hiddenInput() ->label(false) ?>
                        <?= $form->field($clone, 'step') ->hiddenInput() ->label(false) ?>
                        <?= $form->field($clone, 'user_id') ->hiddenInput()->label(false) ?>
                        <?php ActiveForm::end(); ?>
                        <div class="cycle" data-clone="<?= $daily->id ?>">
                            <div class="title">Купить клона  в площадке <?= $daily->range ?> (<?= $daily->price; ?> EUR )</div>
                        </div>
                    <?php else:?>
                        <div class="cycle">
                            <?php
                            //$name = '';
                            /*if($daily->category != \app\models\DailyMoneyEur::CATEGORY_BONUS) {
                                $name = 'площадку '.$daily->range;
                            } else {*/
                                $name = $daily->name;
                            //}
                            ?>
                            <div class="title">Вы уже купили <?= $name ?> </div>
                        </div>
                    <?php endif;?>

                <?php else: ?>
                    <div class="cycle">
                        <?php
//                        $name = '';
//                        if($daily->category != \app\models\DailyMoneyEur::CATEGORY_BONUS) {
//                            $name = 'площадке '.$daily->range;
//                        } else {
                            $name = $daily->name;
                       // }
                        ?>
                        <div class="title">Вы уже завершили  работу в  <?= $name ?> </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php if(!$activeClone || !$activeClone->checkCloneBoll() || !$step): ?>
                        <div class="cycle" data-cycle="<?= $daily->id; ?>">
                            <?php
//                            $name = '';
//                            if($daily->category != \app\models\DailyMoneyEur::CATEGORY_BONUS) {
//                                $name = 'площадке '.$daily->range;
//                            } else {
                                $name = $daily->name;
                            //}
                            ?>
                            <div class="title">Купить место в   <?= $name ?> (<?= $daily->price; ?> EUR )</div>
                        </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>


<div id="dialog-form" title="Вступление в цикл">
    <?php $form = ActiveForm::begin(['options' => ['id' => 'user-step']]) ?>
    <div class="input-block">
        <p>Логин спонсора</p>
        <?= $form->field($model, 'sponsor')
            ->textInput(['value' => Yii::$app->user->identity->getSponsorNameEur() ])
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
