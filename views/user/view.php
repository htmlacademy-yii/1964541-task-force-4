<?php


use app\models\Task;
use app\widgets\StarsWidget; ?>
<div class="left-column">
    <h3 class="head-main"><?= $user->login ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <img class="card-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="191"
                 height="190" alt="Фото пользователя">
            <div class="card-rate">
                <div class="stars-rating big"> <?= StarsWidget::widget(['grade' => $user->rating]) ?>
                </div>
                <span class="current-rate"><?= $user->rating ?></span>
            </div>
        </div>
        <p class="user-description"><?= $user->description ?></p>
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
                <?php
                foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?= $category->name ?></a>
                    </li>
                <?php
                endforeach; ?>
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>, <span
                        class="town-info"><?= $user->city->name ?></span>, <span class="age-info">30</span> лет</p>
        </div>
    </div>
    <h4 class="head-regular">Отзывы заказчиков</h4>
    <?php
    foreach ($user->reviews as $review): ?>
        <div class="response-card">
            <img class="customer-photo" src="../img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <p class="feedback"><?= $review->content ?></p>
                <p class="task">Задание «<a href="#" class="link link--small"><?= $review->task->title ?></a>» выполнено
                </p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small">
                    <?= StarsWidget::widget(['grade' => $review->grade]) ?>
                </div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($review->dt_add) ?></p>
            </div>
        </div>
    <?php
    endforeach; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?= $user->getExecutedTasks()->count(); ?> выполнено, <?= $user->getFailedTasks()->count(); ?> провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd><?= $user->getRatingPosition()?></dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asDate($user->dt_add) ?></dd>
            <dt>Статус</dt>
            <dd><?= Yii::$app->user->identity->isBusy() ? 'Занят' : 'Открыт для новых заказов' ?></dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone"><?= $user->phone ? : 'Не указан' ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email"><?= $user->email ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg"><?= $user->telegram ? : 'Не указан' ?></a>
            </li>
        </ul>
    </div>
</div>