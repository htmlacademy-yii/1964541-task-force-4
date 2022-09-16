<?php

namespace TaskForce\actions;

abstract class ActionAbstract
{
    protected $name;
    protected $internal_name;
    protected $executor_id;
    protected $customer_id;

    public function __construct($customer_id, $executor_id)
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

    abstract public function rightsCheck($user_id): bool;
}
