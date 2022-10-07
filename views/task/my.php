<?php

use app\models\Task;
use app\models\User;
use yii\widgets\Menu; ?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?php
    if (Yii::$app->user->identity->user_type === User::CUSTOMER_STATUS) {
        echo Menu::widget(['items' => [
            ['label' => 'Новые', 'url' => ['task/my', 'type' => Task::STATUS_NEW]],
            ['label' => 'В процессе', 'url' => ['task/my', 'type' => Task::STATUS_IN_WORK]],
            ['label' => 'Закрытые', 'url' => ['task/my', 'type' => Task::STATUS_CLOSED]]],
            'options' => [
                'class' => 'side-menu-list',
            ],
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'activeCssClass'=>'side-menu-item--active',
            'itemOptions'=>['class'=>'side-menu-item'],
        ]);
    } else {
        echo Menu::widget(['items' => [
            ['label' => 'В процессе', 'url' => ['task/my', 'type' => Task::STATUS_IN_WORK]],
            ['label' => 'Просрочено', 'url' => ['task/my', 'type' => Task::STATUS_OVERDUE]],
            ['label' => 'Закрытые', 'url' => ['task/my', 'type' => Task::STATUS_CLOSED]]],
            'options' => [
                'class' => 'side-menu-list',
            ],
            'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
            'activeCssClass'=>'side-menu-item--active',
            'itemOptions'=>['class'=>'side-menu-item'],
        ]);
    }?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular">Новые задания</h3>
    <?php
    foreach ($tasks as $task): ?>
        <div class="task-card">
            <div class="header-task">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id]) ?>"
                   class="link link--block link--big"><?= $task->title ?></a>
                <p class="price price--task"><?= $task->price ?></p>
            </div>
            <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?>
            </p>
            <p class="task-text"><?= $task->description ?></p>
            <div class="footer-task">
                <?= !empty($task->city->name) ? "<p class='info-text town-text'>" . $task->city->name . '</p>' : '' ?>
                <p class="info-text category-text"><?= $task->category->name ?></p>
                <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id]) ?>"
                   class="button button--black">Смотреть Задание</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
