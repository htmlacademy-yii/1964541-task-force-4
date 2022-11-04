<?php

use app\models\Category;
use app\widgets\AvatarWidget;
use yii\widgets\ActiveForm;
?>
<main class="main-content main-content--left container">
    <?= $this->render('optionsMenu') ?>
    <div class="my-profile-form">
            <?php
            $form = ActiveForm::begin(['id' => 'options-form']) ?>
            <h3 class="head-main head-regular">Мой профиль</h3>
            <div class="photo-editing">
                <div>
                    <p class="form-label">Аватар</p>
                    <?= AvatarWidget::widget(['avatar' => Yii::$app->user->identity->avatar, 'width' => 83, 'height' => 83, 'class' => 'avatar-preview']) ?>
                </div>
                <?= $form->field($model, 'file')->fileInput(['hidden' => ''])->label('Сменить аватар', ['class' => 'button button--black']) ?>
            </div>
            <?= $form->field($model, 'login')->textInput(['labelOptions' => ['class' => 'control-label']])?>
            <div class="half-wrapper">
                <?= $form->field($model, 'email')->input('email', ['labelOptions' => ['class' => 'control-label']]) ?>
                <?= $form->field($model, 'birthDate')->input('date', ['format' => 'php:dd.mm.YYYY', 'labelOptions' => ['class' => 'control-label']]) ?>
            </div>
            <div class="half-wrapper">
                <?= $form->field($model, 'phone')->input('tel', ['labelOptions' => ['class' => 'control-label']])?>
                <?= $form->field($model, 'telegram')->textInput(['labelOptions' => ['class' => 'control-label']])?>
            </div>
                <?= $form->field($model, 'description')->textarea(['labelOptions' => ['class' => 'control-label']]) ?>
            <div class="form-group">
                    <?= $form->field($model, 'userCategory')->checkboxList(Category::getCategoriesList(), [
                        'class' => 'checkbox-profile',
                        'itemOptions' => [
                            'labelOptions' => [
                                'class' => 'control-label',
                            ],
                        ],
                    ]) ?>
            </div>
            <input type="submit" class="button button--blue" value="Сохранить">
            <?php ActiveForm::end() ?>
    </div>
</main>
