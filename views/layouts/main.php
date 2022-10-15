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
use yii\widgets\Menu;

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
    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?= Yii::$app->geocoder->getApiKey() ?>&lang=ru_RU"
            type="text/javascript"></script>
    <?php
    $this->head() ?>
</head>
<body>
<?php
$this->beginBody() ?>
<?php if (!Yii::$app->user->isGuest): ?>
    <header class="page-header">
        <nav class="main-nav">
            <a href='#' class="header-logo">
                <img class="logo-image" src="<?= Yii::$app->urlManager->baseUrl ?>/img/logotype.png" width=227
                     height=60
                     alt="taskforce">
            </a>
            <div class="nav-wrapper">
                <?= Menu::widget([
                    'items' => [
                        ['label' => 'Новое', 'url' => ['task/index']],
                        [
                            'label' => 'Мои задания',
                            'url' => ['task/my', 'type' => Task::STATUS_NEW],
                            'active' => Yii::$app->controller->action->id === 'my'
                        ],
                        ['label' => 'Создать задание', 'url' => ['task/add']],
                        [
                            'label' => 'Настройки',
                            'url' => ['user/options'],
                            'active' => Yii::$app->controller->id === 'user'
                        ]
                    ],
                    'options' => [
                        'class' => 'nav-list'
                    ],
                    'linkTemplate' => '<a href="{url}" class="link link--nav">{label}</a>',
                    'activeCssClass' => 'list-item--active',
                    'itemOptions' => ['class' => 'list-item']
                ])
                ?>
            </div>
        </nav>
        <div class="user-block">
            <a href="<?= Yii::$app->urlManager->createUrl(['user/view', 'id' => Yii::$app->user->identity->id]) ?>">
                <?= \app\widgets\AvatarWidget::widget(['avatar' => Yii::$app->user->identity->avatar, 'width' => 55, 'height' => 55, 'class' => 'user-photo']) ?>
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
