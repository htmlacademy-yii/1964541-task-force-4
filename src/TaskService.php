<?php

namespace TaskForce;

use app\models\Response;
use app\models\Task;
use TaskForce\actions\ActionApprove;
use TaskForce\exceptions\ModelSaveException;
use Yii;

class TaskService
{
    private $task;
    private $actionObject;
    private $response;
    private $transaction;

    public function __construct($taskId)
    {
        $this->task = Task::findOne($taskId);
    }

    public function createApproveAction()
    {
        return $this->actionObject = new ActionApprove($this->task->customer_id, $this->task->executor_id, $this->task->id);
    }

    public function actionApprove($executor_id, $response_id)
    {
        $this->task->status = Task::STATUS_IN_WORK;
        $this->task->executor_id = $executor_id;

        $this->response = Response::findOne($response_id);
        $this->response->status = Response::STATUS_ACCEPTED;
    }

    public function saveActionApprove()
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->task->save() && $this->response->save()) {
                $this->transaction->commit();
            }
            throw new ModelSaveException('Не удалось сохранить данные');
        } catch (ModelSaveException $exception) {
            $this->transaction->rollback();
            error_log("Не удалось записать данные. Ошибка: " . $exception->getMessage());
        }
    }




}