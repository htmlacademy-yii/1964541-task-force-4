<?php

namespace TaskForce\actions;

abstract class ActionAbstract
{
    public $name;
    protected $internal_name;
    public $class;
    public $dataAction;
    protected $taskId;
    protected $executor_id;
    protected $customer_id;

    public function __construct($customer_id, $executor_id, $taskId)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
        $this->taskId = $taskId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInternalName(): string
    {
        return $this->internal_name;
    }

    abstract public function rightsCheck(int $user_id): bool;

    abstract public function getLink(): ?string;
}
