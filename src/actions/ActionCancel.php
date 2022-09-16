<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionCancel extends ActionAbstract
{
    protected $name = 'Отказаться';
    protected $internal_name = self::ACTION_REFUSE;

    const ACTION_REFUSE = 'action_refuse';

    public function rightsCheck($user_id): bool
    {
        if ($this->executor_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
