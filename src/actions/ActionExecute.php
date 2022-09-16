<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionExecute extends ActionAbstract
{
    protected $name = 'Выполнить';
    protected $internal_name = self::ACTION_EXECUTE;

    const ACTION_EXECUTE = 'action_execute';

    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}

