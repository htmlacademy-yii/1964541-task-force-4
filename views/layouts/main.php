<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\assets\MainAsset;
use app\models\Task;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

MainAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);
?>
<?php
$this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Taskforce</title>
    <?php
    $this->head() ?>
</head>
<body>
<?php
$this->beginBody() ?>
<?php if (!Yii::$app->user->isGuest): ?>
<div class="<?= $hidden = 'hidden' ?>">
    <header class="page-header">
        <nav class="main-nav">
            <a href='#' class="header-logo">
                <img class="logo-image" src="<?= Yii::$app->urlManager->baseUrl ?>/img/logotype.png" width=227 height=60
                     alt="taskforce">
            </a>
            <div class="nav-wrapper">
                <ul class="nav-list">
                    <li class="list-item list-item--active">
                        <a href="<?= Yii::$app->urlManager->createUrl('task')?>" class="link link--nav">Новое</a>
                    </li>
                    <li class="list-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['task/my', 'type' => Task::STATUS_NEW])?>" class="link link--nav">Мои задания</a>
                    </li>
                    <li class="list-item">
                        <a href="<?= Yii::$app->urlManager->createUrl('task/add')?>" class="link link--nav">Создать задание</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="link link--nav">Настройки</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="user-block">
            <a href="<?= Yii::$app->urlManager->createUrl(['user/view', 'id' => Yii::$app->user->identity->id]) ?>">
                <img class="user-photo" src="<?= Yii::$app->urlManager->baseUrl ?>/img/man-glasses.png" width="55"
                     height="55" alt="Аватар">
            </a>
            <div class="user-menu">
                <p class="user-name"><?= Yii::$app->user->identity->login ?></p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <a href="#" class="link">Настройки</a>
                        </li>
                        <li class="menu-item">
                            <a href="#" class="link">Связаться с нами</a>
                        </li>
                        <li class="menu-item">
                            <a href="<?= Yii::$app->urlManager->createUrl('user/logout') ?>" class="link">Выход из
                                системы</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</div>
<?php endif; ?>

<main>
    <div class="main-content container">
        <?= $content ?>
    </div>
</main>


<?php
$this->endBody() ?>
</body>
</html>
<?php
$this->endPage() ?>
