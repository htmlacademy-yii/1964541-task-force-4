<?php

use yii\helpers\Html;

?>
<div class="task-card">
    <div class="header-task">
        <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $model->id]) ?>"
           class="link link--block link--big"><?= Html::encode($model->title) ?></a>
        <p class="price price--task"><?= Html::encode($model->price) ?></p>
    </div>
    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime($model->dt_add) ?></p>
    <p class="task-text"><?= Html::encode($model->description) ?></p>
    <div class="footer-task">
        <?php if (isset($model->city)): ?>
            <p class="info-text town-text"><?= Html::encode($model->city->name) ?></p>
        <?php endif;?>
        <p class="info-text category-text"><?= $model->category->name ?></p>
        <a href="<?= Yii::$app->urlManager->createUrl(['task/view', 'id' => $model->id])?>" class="button button--black">Смотреть Задание</a>
    </div>
</div>