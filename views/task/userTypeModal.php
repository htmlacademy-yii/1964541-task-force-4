<?php

use app\models\User;
use yii\widgets\ActiveForm;
?>
<section class="pop-up pop-up--act_response pop-up--open">
    <div class="pop-up--wrapper">
        <h4>Выберите роль</h4>
        <p class="pop-up-text">
            Для пользования платформой TaskForce необходимо выбрать вашу роль. Вы будете оставлять задания или выполнять их?
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php
            $form = ActiveForm::begin(['id' => 'modal-form', 'action' => Yii::$app->urlManager->createUrl(['task/modal'])]) ?>
            <?= $form->field($user, 'user_type',)->dropDownList(User::typeAttributeLabels()) ?>
            <input type="submit" class="button button--pop-up button--blue" value="Принять">
            <?php ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>