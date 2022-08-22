<?php

namespace src\actions;

class ActionExecute extends ActionAbstract
{
    protected string $name = 'Выполнить';
    protected string $internal_name = self::ACTION_EXECUTE;

    const ACTION_EXECUTE = 'action_execute';

    protected function rightsCheck(int $executor_id, int $customer_id, int $user_id): bool
    {
        if ($customer_id === $user_id) {
            return true;
        }
        return false;
    }
}

