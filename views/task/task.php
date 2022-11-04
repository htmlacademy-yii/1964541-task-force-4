<?php

use app\models\Category;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php
    echo ListView::widget([
        'dataProvider' => $tasksDataProvider,
        'itemView' => '_post',
        'layout' => "{items}\n<div class='pagination-wrapper'>{pager}</div>",
        'pager' => ['activePageCssClass' => 'pagination-item--active',
            'pageCssClass' => 'pagination-item',
            'options' => ['class' => 'pagination-list'],
            'linkOptions' => ['class' => 'link link--page'],
            'prevPageCssClass' => 'pagination-item mark',
            'prevPageLabel' => '',
            'nextPageCssClass' => 'pagination-item mark',
            'nextPageLabel' => '',
            ]
    ]);
    ?>
</div>
<?php
if (!Yii::$app->user->identity->user_type) {
    echo $this->render('userTypeModal', ['user' => Yii::$app->user->identity]);
}
?>
<div class="right-column">
    <div class="right-card black">
        <div class="search-form">
            <?php
            $form = ActiveForm::begin([
                'id' => 'filterForm',
            ]); ?>
            <h4 class="head-card">Категории</h4>
            <?php
            echo $form->field($model, 'category', ['template' => '{input}{error}'])->checkboxList(
                Category::getCategoriesList(),
                [
                    'class' => 'checkbox-wrapper',
                    'itemOptions' => [
                        'labelOptions' => [
                            'class' => 'control-label',
                        ],
                    ],
                ]
            ); ?>
            <h4 class="head-card">Дополнительно</h4>
            <?php
            echo $form->field($model, 'noResponse', [])->checkbox([
                'labelOptions' => [
                    'class' => 'control-label',
                ]
            ]);
            ?>
            <?php
            echo $form->field($model, 'noAddress', [])->checkbox([]);
            ?>
            <h4 class="head-card">Период</h4>
            <?php
            echo $form->field($model, 'period', ['template' => '{input}{error}'])->dropDownList(
                $model->periodAttributeLabels()
            );
            ?>
            <input type="submit" class="button button--blue" value="Искать">
            <?php
            ActiveForm::end(); ?>
        </div>
    </div>
</div>