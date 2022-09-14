<?php


use app\components\ActionsWidget;
use app\models\Response;
use app\models\Task;
use TaskForce\actions\ActionAccept;
use yii\widgets\ActiveForm; ?>
<div class="left-column">
    <div class="head-wrapper">
        <h3 class="head-main"><?= $task->title ?></h3>
        <p class="price price--big"><?= $task->price ?></p>
    </div>
    <p class="task-description"><?= $task->description ?></p>
    <?php if (!$task->checkUserResponse(Yii::$app->user->id)): ?>
    <?php foreach ($task->getAvailableActions(Yii::$app->user->id) as $action): ?>
        <?= ActionsWidget::widget(['input' => $action, 'taskId' => $task->id]); ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="task-map">
        <img class="map" src="../img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
        <p class="map-address town"><?= $task->city->name ?></p>
        <p class="map-address">Новый арбат, 23, к. 1</p>
    </div>
    <h4 class="head-regular">Отклики на задание</h4>
    <?php foreach ($task->responses as $response): ?>
        <div class="response-card">
            <img class="customer-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="146"
                 height="156" alt="Фото заказчиков">
            <div class="feedback-wrapper">
                <a href="<?= Yii::$app->urlManager->createUrl(['user/view', 'id' => $response->customer->id]) ?>"
                   class="link link--block link--big"><?= $response->executor->login ?></a>
                <div class="response-wrapper">
                    <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span
                                class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span
                                class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
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
                    <a href="<?= Yii::$app->urlManager->createUrl(['task/approve', 'id' => $task->id, 'executor_id' => $response->executor_id, 'response_id' => $response->id]) ?>"
                       class="button button--blue button--small">Принять</a>
                    <a href="<?= Yii::$app->urlManager->createUrl(['task/refuse', 'id' => $task->id, 'response_id' => $response->id]) ?>"
                       class="button button--orange button--small">Отказать</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
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
<?php echo $this->render('modalForms', ['task' => $task, 'reviewForm' => $reviewForm, 'responseForm' => $responseForm]);