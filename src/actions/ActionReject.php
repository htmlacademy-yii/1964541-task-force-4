<?php

namespace TaskForce\actions;

use TaskForce\exceptions\ActionUnavailableException;
use Yii;

class ActionReject extends ActionAbstract
{
    public $name = 'Отменить задание';
    public $class = 'button button--orange action-btn';
    public $dataAction = 'cancel';
    protected $internal_name = self::ACTION_CANCEL;

    const ACTION_CANCEL = 'action_cancel';

    /**
     * Возвращает ссылку
     * @return string|null
     */
    public function getLink(): ?string
    {
        return Yii::$app->urlManager->createUrl(['task/reject', 'id' => $this->taskId]);
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
