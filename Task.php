<?php

class Task
{
    private int $customer_id;
    private int $executor_id;
    private string $current_status = self::STATUS_NEW;

    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_EXECUTED = 'done';
    const STATUS_FAILED = 'failed';
    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPOND = 'respond';
    const ACTION_REFUSE = 'refuse';
    const ACTION_EXECUTE = 'executed';

    public function getAvailableActions(): array
    {
        switch ($this->current_status) {
            case self::STATUS_NEW:
                return [
                    self::ACTION_RESPOND => 'ответить',
                    self::ACTION_CANCEL => 'отменить'
                ];
            case self::STATUS_IN_WORK:
                return [
                    self::ACTION_EXECUTE => 'выполнить',
                    self::ACTION_REFUSE => 'отказаться'
                ];
        }
    }

    public function toRespond()
    {
        return $this->current_status = self::STATUS_IN_WORK;
    }

    public function toRefuse()
    {
        return $this->current_status = self::STATUS_CANCELED;
    }

    public function toExecute()
    {
        return $this->current_status = self::STATUS_EXECUTED;
    }

    public function __construct($customer_id, $executor_id)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
    }

    public function getStatusMap(): array
    {
        return [
            self::ACTION_CANCEL => 'отменить',
            self::ACTION_EXECUTE => 'выполнить',
            self::ACTION_REFUSE => 'отказаться',
            self::ACTION_RESPOND => 'ответить',
            self::STATUS_CANCELED => 'отменено',
            self::STATUS_EXECUTED => 'выполнено',
            self::STATUS_NEW => 'новое',
            self::STATUS_IN_WORK => 'в работе',
            self::STATUS_FAILED => 'провалено'];
    }
}
