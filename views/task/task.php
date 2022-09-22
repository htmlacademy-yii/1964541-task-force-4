<?php

use app\models\Category;
use yii\widgets\ActiveForm;

?>
<div class="left-column">
    <h3 class="head-main head-task">Новые задания</h3>
    <?php
    foreach ($tasks as $task): ?>
        <div class="task-card">
            <div class="header-task">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id]) ?>"
                   class="link link--block link--big"><?= $task->title ?></a>
                <p class="price price--task"><?= $task->price ?></p>
            </div>
            <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?></p>
            <p class="task-text"><?= $task->description ?></p>
            <div class="footer-task">
                <p class="info-text town-text"><?= $task->city->name ?></p>
                <p class="info-text category-text"><?= $task->category->name ?></p>
                <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id])?>" class="button button--black">Смотреть Задание</a>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="pagination-wrapper">
        <ul class="pagination-list">
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">1</a>
            </li>
            <li class="pagination-item pagination-item--active">
                <a href="#" class="link link--page">2</a>
            </li>
            <li class="pagination-item">
                <a href="#" class="link link--page">3</a>
            </li>
            <li class="pagination-item mark">
                <a href="#" class="link link--page"></a>
            </li>
        </ul>
    </div>
</div>
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