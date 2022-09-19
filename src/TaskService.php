<?php

namespace TaskForce;

use app\models\Response;
use app\models\Task;
use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionApprove;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionRefuse;
use TaskForce\actions\ActionReject;
use TaskForce\exceptions\ActionUnavailableException;
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

    public function actionApprove($response_id, $user_id)
    {
        $this->actionCheckRights(new ActionApprove($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);

        $this->response = Response::findOne($response_id);
        $this->response->status = Response::STATUS_ACCEPTED;

        $this->task->status = Task::STATUS_IN_WORK;
        $this->task->executor_id = $this->response->executor_id;
    }

    public function actionResponse($user_id, $responseForm)
    {
        $this->actionCheckRights(new ActionAccept($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);

        $this->response = new Response();
        $this->response->customer_id = $this->task->customer_id;
        $this->response->executor_id = Yii::$app->user->id;
        $responseForm->loadToResponseModel($this->response);
    }

    public function actionRefuse($response_id, $user_id)
    {
        $this->response = Response::findOne($response_id);

        $this->actionCheckRights(new ActionRefuse($this->response->customer_id, $this->response->executor_id, $this->response->task_id), $user_id);

        $this->response->status = Response::STATUS_CANCELED;
    }

    public function actionCancel($user_id)
    {
        $this->actionCheckRights(new ActionCancel($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);
        $this->task->status = task::STATUS_FAILED;
    }

    public function actionReject($user_id)
    {
        $this->actionCheckRights(new ActionReject($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);
        $this->task->status = Task::STATUS_CANCELED;
    }

    public function saveActionResponse()
    {
        if (!$this->response->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function saveActionReject()
    {
        if (!$this->task->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function saveActionCancel()
    {
        if (!$this->task->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function saveActionRefuse()
    {
        if (!$this->response->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
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

    private function actionCheckRights($actionObject, $user_id)
    {
        $this->actionObject = $actionObject;
        if (!$this->actionObject->rightsCheck($user_id)) {
            throw new ActionUnavailableException('Данное действие недоступно');
        }
    }
}