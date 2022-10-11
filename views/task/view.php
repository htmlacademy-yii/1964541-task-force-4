<?php


use app\models\Response;
use app\models\Task;
use app\widgets\ActionsWidget;
use TaskForce\actions\ActionAccept;
use yii\widgets\ActiveForm; ?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= $task->title ?></h3>
        <p class="price price--big"><?= $task->price ?></p>
    </div>
    <p class="task-description"><?= $task->description ?></p>
    <?php if (!$task->checkUserResponse(Yii::$app->user->id)): ?>
    <?php foreach ($task->getAvailableActions(Yii::$app->user->id) as $actionObject): ?>
        <?= $actionObject !== null ? ActionsWidget::widget(['actionObject' => $actionObject]) : ''; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($task->lat): ?>
    <div class="task-map">
        <div id="map" class="map"></div>
            <p class="map-address town"><?= $task->city->name ?></p>
        <p class="map-address"><?= Yii::$app->geocoder->getAddress($task->long . ' ' . $task->lat) ?></p>
    </div>
    <?php endif; ?>
    <?php if ($task->responses): ?>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($task->responses as $response): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="146"
                 height="156" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <a href="<?= Yii::$app->urlManager->createUrl(['user/view', 'id' => $response->customer->id]) ?>"
                   class="link link--block link--big"><?= $response->executor->login ?></a>
                <div class="response-wrapper">
                    <div class="stars-rating small">
                        <?= \app\widgets\StarsWidget::widget(['grade' => $response->executor->rating]) ?>
                    </div>
                    <p class="reviews">2 отзыва</p>
                </div>
                <p class="response-message">
                    <?= $response->content ?>
                </p>

            </div>
            <div class="feedback-wrapper">
                <p class="info-text"><span
                            class="current-time"><?= Yii::$app->formatter->asRelativeTime($response->dt_add) ?></p>
                <p class="price price--small"><?= $response->price ?></p>
            </div>
            <?php if (Yii::$app->user->id === $task->customer_id && $task->status !== Task::STATUS_CANCELED && $task->status !== Task::STATUS_EXECUTED && $task->status !== Task::STATUS_IN_WORK && $response->status !== Response::STATUS_CANCELED): ?>
                <div class="button-popup">
                    <a href="<?= Yii::$app->urlManager->createUrl(['task/approve', 'id' => $task->id, 'response_id' => $response->id]) ?>"
                       class="button button--blue button--small">Принять</a>
                    <a href="<?= Yii::$app->urlManager->createUrl(['task/refuse', 'id' => $task->id, 'response_id' => $response->id]) ?>"
                       class="button button--orange button--small">Отказать</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<div class="right-column">
    <div class="right-card black info-card">
        <h4 class="head-card">Информация о задании</h4>
        <dl class="black-list">
            <dt>Категория</dt>
            <dd><?= $task->category->name ?></dd>
            <dt>Дата публикации</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($task->dt_add) ?></dd>
            <dt>Срок выполнения</dt>
            <dd><?= Yii::$app->formatter->asRelativeTime($task->deadline) ?></dd>
            <dt>Статус</dt>
            <dd><?= $task->getStatusLabel() ?></dd>
        </dl>
    </div>
    <?php if ($task->files): ?>
    <div class="right-card white file-card">
        <h4 class="head-card">Файлы задания</h4>
        <ul class="enumeration-list">
            <?php foreach ($task->files as $file): ?>
            <li class="enumeration-item">
                <a href="<?= Yii::$app->urlManager->createUrl(['task/file', 'fileName' => $file->file])?>" class="link link--block link--clip"><?= $file->file; ?></a>
                <p class="file-size"><?= Yii::$app->formatter->asSize(filesize(Yii::$app->basePath . '/web/uploads/' . $file->file)) ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
    <script type="text/javascript">
        // Функция ymaps.ready() будет вызвана, когда
        // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
        ymaps.ready(init);
        function init(){
            // Создание карты.
            var myMap = new ymaps.Map("map", {
                // Координаты центра карты.
                // Порядок по умолчанию: «широта, долгота».
                // Чтобы не определять координаты центра карты вручную,
                // воспользуйтесь инструментом Определение координат.
                center: [<?= $task->lat ?>, <?= $task->long ?>],
                // Уровень масштабирования. Допустимые значения:
                // от 0 (весь мир) до 19.
                zoom: 14
            });
        }
    </script>
<?php
echo $this->render('cancelPopup', ['task' => $task]);
echo $this->render('responseForm', ['task' => $task, 'responseForm' => $responseForm]);
echo $this->render('reviewForm', ['task' => $task, 'reviewForm' => $reviewForm]);
?>