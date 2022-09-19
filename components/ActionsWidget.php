<?php

namespace app\components;

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionReject;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionCancel;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ActionsWidget extends Widget
{
    public $actionObject;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return Html::a(Html::encode($this->actionObject->name), $this->actionObject->getLink(), ['class' => $this->actionObject->class, 'data-action' => $this->actionObject->dataAction]);
    }
}
