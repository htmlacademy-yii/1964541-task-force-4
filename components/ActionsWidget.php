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
    public $task_id;

    public function init()
    {
        parent::init();

        switch ($this->input) {
            case ActionCancel::class:
                $this->output = 'Отменить задание';
                $this->link = Yii::$app->urlManager->createUrl(['task/cancel', 'id' => $this->task_id]);
                $this->class = 'button button--orange action-btn';
                break;
            case ActionRefuse::class:
                $this->output = 'Отказаться от задания';
                $this->link = Yii::$app->urlManager->createUrl('task/refuse');
                $this->class = 'button button--orange action-btn';
                break;
            case ActionAccept::class:
                $this->output = 'Откликнуться на задание';
                $this->link = Yii::$app->urlManager->createUrl('task/accept');
                $this->class = 'button button--blue action-btn';
                break;
            case ActionExecute::class:
                $this->output = 'Завершить задание';
                $this->link = Yii::$app->urlManager->createUrl('task/execute');
                $this->class = 'button button--pink action-btn';
                break;
        }

    }

    public function run()
    {
        return "<a href='$this->link' class='$this->class'>" . Html::encode($this->output) . '<a>';
    }
}
