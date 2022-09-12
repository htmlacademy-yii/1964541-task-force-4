<?php

namespace app\components;

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ActionsWidget extends Widget
{
    public $input;
    public $output;
    public $link;
    public $class;
    public $taskId;
    public $dataAction;

    public function init()
    {
        parent::init();

        switch ($this->input) {
            case ActionCancel::class:
                $this->output = 'Отменить задание';
                $this->class = 'button button--orange action-btn';
                $this->dataAction = 'cancel';
                $this->link = "href='" . Yii::$app->urlManager->createUrl(['task/cancel', 'id' => $this->taskId]) . "'";
                break;
            case ActionRefuse::class:
                $this->output = 'Отказаться от задания';
                $this->class = 'button button--orange action-btn';
                $this->dataAction = 'refusal';
                break;
            case ActionAccept::class:
                $this->output = 'Откликнуться на задание';
                $this->class = 'button button--blue action-btn';
                $this->dataAction = 'act_response';
                break;
            case ActionExecute::class:
                $this->output = 'Завершить задание';
                $this->class = 'button button--pink action-btn';
                $this->dataAction = 'completion';
                break;
        }

    }

    public function run()
    {
        return "<a $this->link class='$this->class' data-action='$this->dataAction'>" . Html::encode($this->output) . '<a>';
    }
}
