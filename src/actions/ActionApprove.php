<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;

class ActionApprove extends ActionAbstract
{
    public $name = 'Принять';
    protected $internal_name = self::ACTION_APPROVE;

    const ACTION_APPROVE = 'action_approve';

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