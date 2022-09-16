<?php

namespace TaskForce\actions;

abstract class ActionAbstract
{
    protected string $name;
    protected string $internal_name;
    protected int $executor_id;
    protected int $customer_id;

    public function __construct(int $customer_id, int $executor_id)
    {
        $this->customer_id = $customer_id;
        $this->executor_id = $executor_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInternalName(): string
    {
        return $this->internal_name;
    }

    abstract protected function rightsCheck(int $user_id): bool;
}
