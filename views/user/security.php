<?php

use yii\widgets\ActiveForm;

?>
<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item">
                <a href="<?= Yii::$app->urlManager->createUrl('user/options') ?>" class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item side-menu-item--active">
                <a href="#" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
        <?php
        $form = ActiveForm::begin(['id' => 'password-form']) ?>
        <?= $form->field($model, 'oldPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <?= $form->field($model, 'repeatPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <?= $form->field($model, 'userId', ['template' => '{input}'])->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>
        <input type="submit" class="button button--blue" value="Сохранить">
        <?php
        ActiveForm::end() ?>
    </div>
</main>