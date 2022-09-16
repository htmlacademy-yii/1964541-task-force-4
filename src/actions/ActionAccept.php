<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionAccept extends ActionAbstract
{
    protected string $name = 'Принять';
    protected string $internal_name = self::ACTION_ACCEPT;

    const ACTION_ACCEPT = 'action_accept';

    protected function rightsCheck(int $user_id): bool
    {
        if ($this->executor_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
