<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionCancel extends ActionAbstract
{
    public $name = 'Отказаться от задания';
    public $class = 'button button--orange action-btn';
    public $dataAction = 'refusal';
    protected $internal_name = self::ACTION_REFUSE;

    const ACTION_REFUSE = 'action_refuse';

    public function getLink(): ?string
    {
        return null;
    }

    public function rightsCheck($user_id): bool
    {
        if ($this->executor_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
