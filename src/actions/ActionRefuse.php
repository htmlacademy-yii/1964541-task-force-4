<?php

class ActionRefuse extends ActionAbstract
{
    protected string $name = 'Отказаться';
    protected string $internal_name = self::ACTION_REFUSE;

    const ACTION_REFUSE = 'action_refuse';

    protected function rightsCheck(int $executor_id, int $customer_id, int $user_id): bool
    {
        if ($executor_id === $user_id) {
            return true;
        }
        return false;
    }
}
