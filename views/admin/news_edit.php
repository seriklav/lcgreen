<?php
use yii\bootstrap\ActiveForm;
$this->title = 'Новости';
?>
<div class="row">
    <div class="col-sm-12">

        <?php $form = ActiveForm::begin(['id' => 'news']); ?>
        <div class="form-group">
            <label for="comment">Заголовок:</label>
            <?= $form->field($news, 'title')->textInput(['value' => $news->title])->label(false) ?>
        </div>
        <div class="form-group">
            <label for="comment">Текст:</label>
            <?= $form->field($news, 'desc_text')->textarea(['rows' => '6','value' => $news->desc_text])->label(false) ?>
        </div>
        <button type="submit" class="btn btn-primary btn-lg" style="display: block; margin: auto;">Обновить</button>
        <?php ActiveForm::end(); ?>
    </div>

</div>
