<?php
require_once 'Task.php';

$task = new Task(1, 2);

$array = $task->getAvailableActions();
var_dump($array);

echo assert($task->getNextStatus('action_cancel') == Task::STATUS_CANCELED, 'cancel action');
$task->actionAccept();
echo assert($task->getCurrentStatus() == Task::STATUS_IN_WORK, 'accept task');
