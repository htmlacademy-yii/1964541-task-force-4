<?php

namespace TaskForce\actions;

abstract class ActionAbstract
{
    public $name;
    public $class;
    public $dataAction;
    protected $internal_name;
    protected $taskId;
    protected $executor_id;
    protected $customer_id;

    abstract public function rightsCheck(int $user_id): bool;

    public function __construct($customer_id, $executor_id, $taskId)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
        $this->taskId = $taskId;
    }

    /**
     * Геттер имени
     * @return string Название действия
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Геттер англ имени
     * @return string Название действия
     */
    public function getInternalName(): string
    {
        return $this->internal_name;
    }
}
