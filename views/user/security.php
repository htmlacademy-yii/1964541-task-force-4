<?php

use yii\widgets\ActiveForm;

?>
<main class="main-content main-content--left container">
    <?= $this->render('optionsMenu') ?>
    <div class="my-profile-form">
        <?php
        $form = ActiveForm::begin(['id' => 'password-form']) ?>
        <?= $form->field($model, 'oldPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <?= $form->field($model, 'repeatPassword')->passwordInput(['labelOptions' => ['class' => 'control-label']]) ?>
        <input type="submit" class="button button--blue" value="Сохранить">
        <?php
        ActiveForm::end() ?>
    </div>
</main>