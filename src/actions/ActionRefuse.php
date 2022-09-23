<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionRefuse extends ActionAbstract
{
    public $name = 'Отказать';
    protected $internal_name = self::ACTION_REFUSE;

    const ACTION_REFUSE = 'action_refuse';

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