<?php
use yii\widgets\ActiveForm;
?>
<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item side-menu-item--active">
                <a class="link link--nav">Мой профиль</a>
            </li>
            <li class="side-menu-item">
                <a href="#" class="link link--nav">Безопасность</a>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
            <?php
            $form = ActiveForm::begin(['id' => 'options-form']) ?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <img class="avatar-preview" src="../img/man-glasses.png" width="83" height="83">
                </div>
                <input hidden value="Сменить аватар" type="file" id="button-input">
                <label for="button-input" class="button button--black"> Сменить аватар</label>
                <?= $form->field($model, 'file')->fileInput(['id' => 'button-input', 'class' => 'button button--black']) ?>
            </div>
            <?= $form->field($model, 'login')->textInput(['labelOptions' => ['class' => 'control-label']])?>
            <div class="half-wrapper">
                <?= $form->field($model, 'email')->input('email', ['labelOptions' => ['class' => 'control-label']]) ?>
                <?= $form->field($model, 'birthDate')->input('date', ['labelOptions' => ['class' => 'control-label']]) ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($model, 'phone')->input('tel', ['labelOptions' => ['class' => 'control-label']])?>
                <?= $form->field($model, 'telegram')->textInput(['labelOptions' => ['class' => 'control-label']])?>
            </div>
                <?= $form->field($model, 'description')->textarea(['labelOptions' => ['class' => 'control-label']]) ?>
            <div class="form-group">
                <p class="form-label">Выбор специализаций</p>
                <div class="checkbox-profile">
                    <label class="control-label" for="сourier-services">
                        <input type="checkbox" id="сourier-services" checked>
                        Курьерские услуги</label>
                    <label class="control-label" for="cargo-transportation">
                        <input id="cargo-transportation" type="checkbox">
                        Грузоперевозки</label>
                    <label class="control-label" for="cleaning">
                        <input id="cleaning" type="checkbox">
                        Клининг</label>
                    <label class="control-label" for="computer-help">
                        <input id="computer-help" type="checkbox" checked>
                        Компьютерная помощь</label>
                </div>
            </div>
            <input type="submit" class="button button--blue" value="Сохранить">
            <?php ActiveForm::end() ?>
    </div>
</main>
