<?php
namespace TaskForce;

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionReject;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionCancel;
use TaskForce\exceptions\ActionNotExistsException;
use TaskForce\exceptions\ActionUnavailableException;
use TaskForce\exceptions\StatusNotExistsException;

class Task
{
    private int $customer_id;
    private int $executor_id;
    private string $current_status = self::STATUS_NEW;

    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_EXECUTED = 'executed';
    const STATUS_FAILED = 'failed';

    public function __construct(int $customer_id, int $executor_id)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
    }

    public function getCurrentStatus(): string
    {
        return $this->current_status;
    }

    public function getNextStatus(string $action): string
    {
        return match ($action) {
            ActionAccept::class => self::STATUS_IN_WORK,
            ActionReject::class => self::STATUS_CANCELED,
            ActionExecute::class => self::STATUS_EXECUTED,
            ActionCancel::class => self::STATUS_FAILED,
            default => throw new ActionNotExistsException('Данное действие не предусмотрено'),
        };
    }

    public function getAvailableActions(int $id): array
    {
        switch ($this->current_status) {
            case self::STATUS_NEW:
                return $id === $this->customer_id ? [ActionReject::class] : [ActionAccept::class, ActionCancel::class];
            case self::STATUS_IN_WORK:
                return $id === $this->customer_id ? [ActionExecute::class, ActionReject::class] : [ActionCancel::class];
            default:
                throw new StatusNotExistsException('Статус не существует');
        }
    }

    public function actionAccept(int $id): void
    {
        if (in_array(ActionAccept::class, $this->getAvailableActions($id))) {
            $this->current_status = self::STATUS_IN_WORK;
        } else {
            throw new ActionUnavailableException('Совершить данное действие невозможно');
        }
    }

    public function actionRefuse(int $id): void
    {
        if (in_array(ActionCancel::class, $this->getAvailableActions($id))) {
            $this->current_status = self::STATUS_FAILED;
        } else {
            throw new ActionUnavailableException('Совершить данное действие невозможно');
        }
    }

    public function actionExecute(int $id): void
    {
        if (in_array(ActionExecute::class, $this->getAvailableActions($id))) {
            $this->current_status = self::STATUS_EXECUTED;
        } else {
            throw new ActionUnavailableException('Совершить данное действие невозможно');
        }
    }

    public function actionCancel(int $id): void
    {
        if (in_array(ActionReject::class, $this->getAvailableActions($id))) {
            $this->current_status = self::STATUS_CANCELED;
        } else {
            throw new ActionUnavailableException('Совершить данное действие невозможно');
        }
    }

    public function getStatusMap(): array
    {
        return [
            ActionReject::class => 'отменить',
            ActionExecute::class => 'выполнить',
            ActionCancel::class => 'отказаться',
            ActionAccept::class => 'принять',
            self::STATUS_CANCELED => 'отменено',
            self::STATUS_EXECUTED => 'выполнено',
            self::STATUS_NEW => 'новое',
            self::STATUS_IN_WORK => 'в работе',
            self::STATUS_FAILED => 'провалено'
        ];
    }
}
