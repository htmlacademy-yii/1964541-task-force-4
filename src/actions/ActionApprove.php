<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionApprove extends ActionAbstract
{
    public $name = 'Принять';
    protected $internal_name = self::ACTION_APPROVE;

    const ACTION_APPROVE = 'action_approve';

    public function getLink(): ?string
    {
        return null;
    }

    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}