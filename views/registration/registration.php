<?php

use app\models\City;
use yii\widgets\ActiveForm;
?>
<div class="center-block">
    <div class="registration-form regular-form">
        <?php
        $form = ActiveForm::begin(['id' => 'registration-form'])?>
        <h3 class="head-main head-task">Регистрация нового пользователя</h3>
        <?php echo $form->field($model, 'login')->textInput(['class' => 'control-label']);  ?>
        <div class="half-wrapper">
            <?php echo $form->field($model, 'email')->textInput(['class' => 'control-label']);  ?>
            <?php echo $form->field($model, 'cityId')->dropDownList(City::getCityList());  ?>
        </div>
        <div class="half-wrapper">
        <?php echo $form->field($model, 'password')->passwordInput(['class' => 'control-label']);  ?>
        </div>
        <div class="half-wrapper">
            <?php echo $form->field($model, 'passwordRepeat')->passwordInput(['class' => 'control-label']);  ?>
        </div>
        <?php echo $form->field($model, 'isUser')->checkbox(['class' => 'control-label']);  ?>
        <input type="submit" class="button button--blue" value="Создать аккаунт">
        <?php ActiveForm::end() ?>
    </div>
</div>