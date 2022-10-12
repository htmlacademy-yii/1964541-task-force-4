<?php
use yii\widgets\ActiveForm;
?>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin(['id' => 'review-form', 'action' => Yii::$app->urlManager->createUrl(['task/review'])]) ?>
            <?= $form->field($reviewForm, 'content')->textarea(['labelOptions' => ['class' => 'control-label']]) ?>
            <?= $form->field($reviewForm, 'grade')->input('number', ['labelOptions' => ['class' => 'control-label']]) ?>
            <?= $form->field($reviewForm, 'taskId', ['template' => '{input}'])->hiddenInput(['value' => $task->id])->label(false) ?>
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
<script>
    let popupReview = document.querySelector('.pop-up--completion');
    let parentItems = popupReview.querySelector('.stars-rating');
    let allItems = parentItems.querySelectorAll('span');
    let activeItems = parentItems.querySelectorAll('.fill-star');

    parentItems.addEventListener('click', (evt) => {
        var myTarget = evt.target;
        // Длина массива
        var i = allItems.length;
        // Находи выбранный элемент в массиве и заносим его индекс в переменную
        while(i--) {
            if(allItems[i] == myTarget) {
                var currentIndex = i;
                break;
            }
        }
        cStars(currentIndex);
    });

    var cStars = function(nowPos) {
        // Убираем у всех элементов active
        for (var i = 0; allItems.length > i; i++) {
            allItems[i].classList.remove('fill-star');
        }
        // Добавляет активный класс всем элементам до выбранного, включая выбранный
        for (var i = 0; nowPos + 1 > i; i++) {
            allItems[i].classList.toggle('fill-star');
        }
    }

</script>

