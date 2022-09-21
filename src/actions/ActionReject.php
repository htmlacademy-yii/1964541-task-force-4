<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;
use Yii;

class ActionReject extends ActionAbstract
{
    public $name = 'Отменить задание';
    public $class = 'button button--orange action-btn';
    public $dataAction = 'cancel';
    protected $internal_name = self::ACTION_CANCEL;

    const ACTION_CANCEL = 'action_cancel';

    public function getLink(): ?string
    {
        return Yii::$app->urlManager->createUrl(['task/reject', 'id' => $this->taskId]);
    }

    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
