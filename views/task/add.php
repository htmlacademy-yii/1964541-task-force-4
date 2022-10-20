<?php

use app\models\Category;
use yii\widgets\ActiveForm;

?>
<div class="add-task-form regular-form">
    <?php
    $form = ActiveForm::begin(['id' => 'add-task-form']) ?>
    <h3 class="head-main head-main">Публикация нового задания</h3>
    <?= $form->field($model, 'title')->textInput(['labelOptions' => 'control-label']) ?>
    <?= $form->field($model, 'description')->textarea(['labelOptions' => 'control-label']) ?>
    <?= $form->field($model, 'category')->dropDownList(Category::getCategoriesList(), ['labelOptions' => 'control-label']) ?>
    <?= $form->field($model, 'address')->textInput(['labelOptions' => 'control-label', 'id' => 'autoComplete'])?>
    <div class="half-wrapper">
        <?= $form->field($model, 'price')->input('number', ['class' => 'budget-icon', 'labelOptions' => 'control-label']) ?>
        <?= $form->field($model, 'deadline')->input('date', ['labelOptions' => 'control-label'])?>
    </div>
    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'class' => 'new-file']) ?>
    <input type="submit" class="button button--blue" value="Опубликовать">
    <?php
    ActiveForm::end() ?>
</div>
<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js');
$this->registerJsFile('/js/autoComplete.js');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css');?>