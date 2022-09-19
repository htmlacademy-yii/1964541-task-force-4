<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionExecute extends ActionAbstract
{
    public $name = 'Завершить задание';
    public $class = 'button button--pink action-btn';
    public $dataAction = 'completion';
    protected $internal_name = self::ACTION_EXECUTE;

    const ACTION_EXECUTE = 'action_execute';

    public function getLink()
    {
    }

    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}

