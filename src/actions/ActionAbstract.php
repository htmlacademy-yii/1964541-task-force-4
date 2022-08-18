<?php

namespace TaskForce\actions;

abstract class ActionAbstract
{
    protected string $name;
    protected string $internal_name;

    public function getName(): string
    {
        return $this->name;
    }

    public function getInternalName(): string
    {
        return $this->internal_name;
    }

    abstract protected function rightsCheck(int $executor_id, int $customer_id, int $user_id): bool;
}
