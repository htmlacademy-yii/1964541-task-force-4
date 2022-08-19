<?php

namespace TaskForce\actions;

class ActionAccept extends ActionAbstract
{
    protected string $name = 'Принять';
    protected string $internal_name = self::ACTION_ACCEPT;

    const ACTION_ACCEPT = 'action_accept';

    protected function rightsCheck(int $executor_id, int $customer_id, int $user_id): bool
    {
        if ($executor_id === $user_id) {
            return true;
        }
        return false;
    }
}
