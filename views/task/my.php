<?php

use app\models\Task;
use app\models\User; ?>
<div class="left-menu">
    <h3 class="head-main head-task">Мои задания</h3>
    <?php if (Yii::$app->user->identity->user_type === User::CUSTOMER_STATUS): ?>
    <ul class="side-menu-list">
        <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_NEW ? 'side-menu-item--active' : '' ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_NEW]) ?>" class="link link--nav">Новые</a>
        </li>
        <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_IN_WORK ? 'side-menu-item--active' : '' ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_IN_WORK]) ?>" class="link link--nav">В процессе</a>
        </li>
        <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_CLOSED ? 'side-menu-item--active' : '' ?>">
            <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_CLOSED]) ?>" class="link link--nav">Закрытые</a>
        </li>
    </ul>
    <?php else: ?>
        <ul class="side-menu-list">
            <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_IN_WORK ? 'side-menu-item--active' : '' ?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_IN_WORK]) ?>" class="link link--nav">В процессе</a>
            </li>
            <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_OVERDUE ? 'side-menu-item--active' : '' ?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_OVERDUE]) ?>" class="link link--nav">Просрочено</a>
            </li>
            <li class="side-menu-item <?= $active = Yii::$app->request->get('type') === Task::STATUS_CLOSED ? 'side-menu-item--active' : '' ?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_CLOSED]) ?>" class="link link--nav">Закрытые</a>
            </li>
        </ul>
    <?php endif; ?>
</div>
<div class="left-column left-column--task">
    <h3 class="head-main head-regular">Новые задания</h3>
    <?php
    foreach ($tasks as $task): ?>
    <div class="task-card">
        <div class="header-task">
            <a  href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id]) ?>" class="link link--block link--big"><?= $task->title ?></a>
            <p class="price price--task"><?= $task->price ?></p>
        </div>
        <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?></p>
        <p class="task-text"><?= $task->description ?></p>
        <div class="footer-task">
            <p class="info-text town-text"><?= $task->city->name ?></p>
            <p class="info-text category-text"><?= $task->category->name ?></p>
            <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $task->id]) ?>" class="button button--black">Смотреть Задание</a>
        </div>
    </div>
    <?php  endforeach; ?>
</div>
