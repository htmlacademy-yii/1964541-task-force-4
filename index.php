<?php
require_once 'vendor/autoload.php';

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use TaskForce\Task;

$task = new Task(1, 2);

$array = $task->getAvailableActions(1);

echo assert($task->getAvailableActions(1) == [ActionCancel::class], 'object return check');

echo assert($task->getAvailableActions(2) == [ActionAccept::class, ActionRefuse::class], 'object return check');

echo assert($task->getNextStatus(ActionCancel::class) == Task::STATUS_CANCELED, 'cancel action');

echo assert($task->getNextStatus(ActionExecute::class) == Task::STATUS_EXECUTED, 'execute action');

$task->actionAccept(2);
echo assert($task->getCurrentStatus() == Task::STATUS_IN_WORK, 'accept task');

echo assert($task->getAvailableActions(1) == [ActionExecute::class, ActionCancel::class], 'object return check');

$task->actionCancel(1);
echo assert($task->getCurrentStatus() == Task::STATUS_CANCELED, 'cancel task');


