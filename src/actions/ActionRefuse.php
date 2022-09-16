<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionRefuse extends ActionAbstract
{
    protected string $name = 'Отказаться';
    protected string $internal_name = self::ACTION_REFUSE;

    const ACTION_REFUSE = 'action_refuse';

    protected function rightsCheck(int $user_id): bool
    {
        if ($this->executor_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}
