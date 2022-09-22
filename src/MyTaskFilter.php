<?php
namespace TaskForce;

use app\models\Task;

class MyTaskFilter
{
    public $task;

    public function __construct(string $type, int $userId)
    {
        $this->task = Task::findAll(['status' => $type, 'customer_id' => $userId]);
    }

    public function getFilteredTasks()
    {
        return $this->task;
    }
}