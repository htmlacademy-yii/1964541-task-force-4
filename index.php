<?php
require_once 'Task.php';

$task = new Task(1, 2);

$array = $task->getAvailableActions(1);
var_dump($array);

echo assert($task->getNextStatus('action_cancel') == Task::STATUS_CANCELED, 'cancel action');

$task->actionAccept(2);
echo assert($task->getCurrentStatus() == Task::STATUS_IN_WORK, 'accept task');

$task->actionCancel(1);
echo assert($task->getCurrentStatus() == Task::STATUS_CANCELED, 'execute task');
