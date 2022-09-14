<?php
use yii\widgets\ActiveForm;
?>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a href="<?=Yii::$app->urlManager->createUrl(['task/cancel', 'id' => $task->id])?>" class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin(['id' => 'review-form', 'action' => Yii::$app->urlManager->createUrl(['task/review', 'id' => $task->id])]) ?>
            <?= $form->field($reviewForm, 'content')->textarea(['labelOptions' => ['class' => 'control-label']]) ?>
            <?= $form->field($reviewForm, 'grade')->input('number', ['labelOptions' => ['class' => 'control-label']]) ?>
            <p class="completion-head control-label">Оценка работы</p>
            <div class="stars-rating big active-stars">
                <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
            </div>
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin(['id' => 'response-form', 'action' => Yii::$app->urlManager->createUrl(['task/response', 'id' => $task->id])]) ?>
            <?= $form->field($responseForm, 'content')->textarea(['labelOptions' => ['class' => 'control-label']]) ?>
            <?= $form->field($responseForm, 'price')->input('number', ['labelOptions' => ['class' => 'control-label']]) ?>
            <input type="submit" class="button button--pop-up button--blue" value="Принять">
            <?php ActiveForm::end() ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>