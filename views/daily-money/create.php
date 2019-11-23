<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DailyMoney */

$this->title = Yii::t('app', 'Создать ежедневные деньги');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ежедневные деньги'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="daily-money-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
