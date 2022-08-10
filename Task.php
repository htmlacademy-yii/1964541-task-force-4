<?php

class Task
{
    private int $customer_id;
    private int $executor_id;
    private string $current_status = self::STATUS_NEW;

    const STATUS_NEW = 'status_new';
    const STATUS_CANCELED = 'status_canceled';
    const STATUS_IN_WORK = 'status_in_work';
    const STATUS_EXECUTED = 'status_executed';
    const STATUS_FAILED = 'status_failed';
    const ACTION_CANCEL = 'action_cancel';
    const ACTION_ACCEPT = 'action_respond';
    const ACTION_REFUSE = 'action_refuse';
    const ACTION_EXECUTE = 'action_executed';

    public function __construct($customer_id, $executor_id)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
    }

    public function getCurrentStatus(): string
    {
        return $this->current_status;
    }

    public function getNextStatus($action): string
    {
        return match ($action) {
            self::ACTION_ACCEPT => self::STATUS_IN_WORK,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_EXECUTE => self::STATUS_EXECUTED,
            self::ACTION_REFUSE => self::STATUS_FAILED,
            default => 'Данное действие не предусмотрено',
        };
    }

    public function getAvailableActions(): array
    {
        switch ($this->current_status) {
            case self::STATUS_NEW:
                return [
                    self::ACTION_ACCEPT => 'принять',
                    self::ACTION_CANCEL => 'отменить'
                ];
            case self::STATUS_IN_WORK:
                return [
                    self::ACTION_EXECUTE => 'выполнить',
                    self::ACTION_REFUSE => 'отказаться'
                ];
        }
    }

    public function actionAccept(): void
    {
        $this->current_status = self::STATUS_IN_WORK;
    }

    public function actionRefuse(): void
    {
        $this->current_status = self::STATUS_CANCELED;
    }

    public function actionExecute(): void
    {
        $this->current_status = self::STATUS_EXECUTED;
    }

    public function actionCancel(): void
    {
        $this->current_status = self::STATUS_CANCELED;
    }

    public function getStatusMap(): array
    {
        return [
            self::ACTION_CANCEL => 'отменить',
            self::ACTION_EXECUTE => 'выполнить',
            self::ACTION_REFUSE => 'отказаться',
            self::ACTION_ACCEPT => 'принять',
            self::STATUS_CANCELED => 'отменено',
            self::STATUS_EXECUTED => 'выполнено',
            self::STATUS_NEW => 'новое',
            self::STATUS_IN_WORK => 'в работе',
            self::STATUS_FAILED => 'провалено'
        ];
    }
}
