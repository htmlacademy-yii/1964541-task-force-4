<?php


use app\widgets\AvatarWidget;
use app\widgets\StarsWidget;
use yii\helpers\Html; ?>
<div class="left-column">
    <h3 class="head-main"><?= Html::encode($user->login) ?></h3>
    <div class="user-card">
        <div class="photo-rate">
            <?= AvatarWidget::widget(['avatar' => $user->avatar, 'height' => 190, 'width' => 191])?>
            <div class="card-rate">
                <div class="stars-rating big"> <?= StarsWidget::widget(['grade' => $user->getUserRating()]) ?>
                </div>
                <span class="current-rate"><?= $user->getUserRating() ?></span>
            </div>
        </div>
        <p class="user-description"><?= Html::encode($user->description) ?></p>
    </div>
    <div class="specialization-bio">
        <?php if (!empty($user->categories)): ?>
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
        <?php endif; ?>
        <div class="bio">
            <p class="head-info">Био</p>
            <p class="bio-info"><span class="country-info">Россия</span>, <span
                        class="town-info"><?= !empty($user->city) ? Html::encode($user->city->name) : '' ?></span> <span class="age-info"><?= $user->getUserAge() ?></p>
        </div>
    </div>
    <?php if (!empty($user->reviews)): ?>
    <h4 class="head-regular">Отзывы заказчиков</h4>
    <?php
    foreach ($user->reviews as $review): ?>
        <div class="response-card">
            <?= AvatarWidget::widget(['avatar' => $review->executor->avatar, 'width' => 120, 'height' => 127]) ?>
            <div class="feedback-wrapper">
                <p class="feedback"><?= Html::encode($review->content) ?></p>
                <p class="task">Задание «<a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $review->task->id]) ?>" class="link link--small"><?= Html::encode($review->task->title) ?></a>» выполнено
                </p>
            </div>
            <div class="feedback-wrapper">
                <div class="stars-rating small">
                    <?= StarsWidget::widget(['grade' => Html::encode($review->grade)]) ?>
                </div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($review->dt_add) ?></p>
            </div>
        </div>
    <?php
    endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
            <dd><?= $user->getExecutedTasks()->count(); ?> выполнено, <?= $user->getFailedTasks()->count(); ?> провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd><?= $user->getRatingPosition() ?></dd>
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
                <a href="#" class="link link--block link--phone"><?= Html::encode($user->phone) ? : 'Не указан' ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email"><?= Html::encode($user->email) ?></a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg"><?= Html::encode($user->telegram) ? : 'Не указан' ?></a>
            </li>
        </ul>
    </div>
</div>