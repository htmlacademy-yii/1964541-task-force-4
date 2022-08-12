<?php

class ActionCancel extends ActionAbstract
{
    protected string $name = 'Отменить';
    protected string $internal_name = self::ACTION_CANCEL;

    const ACTION_CANCEL = 'action_cancel';

    protected function rightsCheck(int $executor_id, int $customer_id, int $user_id): bool
    {
        if ($customer_id === $user_id) {
            return true;
        }
        return false;
    }
}
