<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= $task->title ?></h3>
        <p class="price price--big"><?= $task->price ?></p>
    </div>
    <p class="task-description"><?= $task->description ?></p>
    <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
    <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
    <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
    <div class="task-map">
        <img class="map" src="../img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town"><?= $task->city->name ?></p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($responses as $response): ?>
    <div class="response-card">
        <img class="customer-photo" src="../img/man-glasses.png" width="146" height="156" alt="Фото заказчиков">
        <div class="feedback-wrapper">
            <a href="#" class="link link--block link--big"><?= $response->customer->login ?></a>
            <div class="response-wrapper">
                <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                <p class="reviews">2 отзыва</p>
            </div>
            <p class="response-message">
                <?= $response->content ?>
            </p>

        </div>
        <div class="feedback-wrapper">
            <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->format($response->dt_add, 'relativeTime') ?></p>
            <p class="price price--small"><?= $response->price ?></p>
        </div>
        <div class="button-popup">
            <a href="#" class="button button--blue button--small">Принять</a>
            <a href="#" class="button button--orange button--small">Отказать</a>
        </div>
    </div>
</div>
<?php endforeach; ?>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= $task->category->name ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->format($task->dt_add, 'relativeTime') ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?= Yii::$app->formatter->format($task->deadline, 'relativeTime') ?></dd>
            <dt>Статус</dt>
            <dd><?= $task->getStatusLabel() ?></dd>
        </dl>
    </div>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                <p class="file-size">356 Кб</p>
            </li>
            <li class="enumeration-item">
                <a href="#" class="link link--block link--clip">information.docx</a>
                <p class="file-size">12 Кб</p>
            </li>
        </ul>
    </div>
</div>