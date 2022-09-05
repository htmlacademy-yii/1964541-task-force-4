<?php
<<<<<<< HEAD

=======
>>>>>>> 0b9615f
?>
<div class="left-column">
    <h3 class="head-main"><?= $user->login ?></h3>
    <div class="user-card">
        <div class="photo-rate">
<<<<<<< HEAD
            <img class="card-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="191"
                 height="190" alt="Фото пользователя">
            <div class="card-rate">
                <div class="stars-rating big"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span
                            class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span>
                </div>
                <span class="current-rate"><?= $user->rating ?></span>
            </div>
        </div>
        <p class="user-description"><?= $user->description ?></p>
=======
            <img class="card-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="191" height="190" alt="Фото пользователя">
            <div class="card-rate">
                <div class="stars-rating big"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <span class="current-rate"><?= $user->rating ?></span>
            </div>
        </div>
        <p class="user-description">
            Внезапно, ключевые особенности структуры проекта
            неоднозначны и будут подвергнуты целой серии
            независимых исследований. Следует отметить, что
            высококачественный прототип будущего проекта, в
            своём классическом представлении, допускает
            внедрение своевременного выполнения сверхзадачи.
        </p>
>>>>>>> 0b9615f
    </div>
    <div class="specialization-bio">
        <div class="specialization">
            <p class="head-info">Специализации</p>
            <ul class="special-list">
<<<<<<< HEAD
                <?php
                foreach ($user->categories as $category): ?>
                    <li class="special-item">
                        <a href="#" class="link link--regular"><?= $category->name ?></a>
                    </li>
                <?php
                endforeach; ?>
=======
                <li class="special-item">
                    <a href="#" class="link link--regular">Ремонт бытовой техники</a>
                </li>
                <li class="special-item">
                    <a href="#" class="link link--regular">Курьер</a>
                </li>
                <li class="special-item">
                    <a href="#" class="link link--regular">Оператор ПК</a>
                </li>
>>>>>>> 0b9615f
            </ul>
        </div>
        <div class="bio">
            <p class="head-info">Био</p>
<<<<<<< HEAD
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
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span
                            class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span>
                </div>
                <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($review->dt_add) ?></p>
            </div>
        </div>
    <?php
    endforeach; ?>
=======
            <p class="bio-info"><span class="country-info">Россия</span>, <span class="town-info"><?= $user->city->name ?></span>, <span class="age-info">30</span> лет</p>
        </div>
    </div>
    <h4 class="head-regular">Отзывы заказчиков</h4>
    <div class="response-card">
        <img class="customer-photo" src="../img/man-coat.png" width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«Кумар сделал всё в лучшем виде. Буду обращаться к нему в
                будущем, если возникнет такая необходимость!»</p>
            <p class="task">Задание «<a href="#" class="link link--small">Повесить полочку</a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="info-text"><span class="current-time">25 минут </span>назад</p>
        </div>
    </div>
    <div class="response-card">
        <img class="customer-photo" src="../img/man-sweater.png" width="120" height="127" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <p class="feedback">«Кумар сделал всё в лучшем виде. Буду обращаться к нему в
                будущем, если возникнет такая необходимость!»</p>
            <p class="task">Задание «<a href="#" class="link link--small">Повесить полочку</a>» выполнено</p>
        </div>
        <div class="feedback-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="info-text"><span class="current-time">25 минут </span>назад</p>
        </div>
    </div>
>>>>>>> 0b9615f
</div>
<div class="right-column">
    <div class="right-card black">
        <h4 class="head-card">Статистика исполнителя</h4>
        <dl class="black-list">
            <dt>Всего заказов</dt>
<<<<<<< HEAD
            <dd><?= $user->getExecutedTasks()->count(); ?> выполнено, <?= $user->getFailedTasks()->count(); ?> провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd><?= $user->getRatingPosition()?></dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->asDate($user->dt_add) ?></dd>
=======
            <dd>4 выполнено, 0 провалено</dd>
            <dt>Место в рейтинге</dt>
            <dd>25 место</dd>
            <dt>Дата регистрации</dt>
            <dd><?= Yii::$app->formatter->format($user->dt_add, 'date') ?></dd>
>>>>>>> 0b9615f
            <dt>Статус</dt>
            <dd>Открыт для новых заказов</dd>
        </dl>
    </div>
    <div class="right-card white">
        <h4 class="head-card">Контакты</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--phone">+7 (906) 256-06-08</a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--email">super-pavel@mail.ru</a>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--tg">@superpasha</a>
            </li>
        </ul>
    </div>
</div>