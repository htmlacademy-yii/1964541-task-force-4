<?php

?>
<div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <?=\yii\widgets\Menu::widget(['items' => [
    ['label' => 'Мой профиль', 'url' => ['user/options']],
    ['label' => 'Безопасность', 'url' => ['user/security']]],
        'options' => [
            'class' => 'side-menu-list',
        ],
        'activeCssClass'=>'side-menu-item--active',
        'itemOptions'=>['class'=>'side-menu-item'],
    ]);?>
</div>
