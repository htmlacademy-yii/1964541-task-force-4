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

    /**
     * Метод заглушка
     * @return string|null
     */
    public function getLink(): ?string
    {
        return null;
    }

    /**
     * Проверяет доступ юзера к определенному действию
     * @param $user_id ID юзера в сесии
     * @return bool Пользователь имеет доступ или нет
     * @throws ActionUnavailableException Действие недоступно текущему пользователю
     */
    public function rightsCheck($user_id): bool
    {
        if ($this->customer_id === $user_id) {
            return true;
        }
        throw new ActionUnavailableException('Действие вам недоступно');
    }
}

