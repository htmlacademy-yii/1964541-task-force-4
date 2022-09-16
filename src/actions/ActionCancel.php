<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionCancel extends ActionAbstract
{
    protected $name = 'Отменить';
    protected $internal_name = self::ACTION_CANCEL;

    const ACTION_CANCEL = 'action_cancel';

    protected function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
