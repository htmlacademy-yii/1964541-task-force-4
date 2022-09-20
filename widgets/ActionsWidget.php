<?php

namespace app\widgets;

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionReject;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionCancel;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Создает кнопки для работы с действиями
 * @param object $actionObject - сюда должен передаваться объект действия, кнопку для которого необходимо отрисовать
 *
 * @return string - Возвращает кнопку с параметрами, заданными в объекте.
 */
class ActionsWidget extends Widget
{
    public $actionObject;

    public function run()
    {
        return Html::a(Html::encode($this->actionObject->name), $this->actionObject->getLink(), ['class' => $this->actionObject->class, 'data-action' => $this->actionObject->dataAction]);
    }
}
