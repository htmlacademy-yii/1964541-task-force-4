<?php

namespace TaskForce;

use app\models\Response;
use app\models\Review;
use app\models\Task;
use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionApprove;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use TaskForce\actions\ActionReject;
use TaskForce\exceptions\ActionUnavailableException;
use TaskForce\exceptions\ModelSaveException;
use Yii;

class TaskService
{
    private $task;
    private $actionObject;
    private $activeRecordModel;
    private $transaction;

    public function __construct($taskId)
    {
        $this->task = Task::findOne($taskId);
    }

    public function actionApprove($response_id, $user_id)
    {
        $this->actionCheckRights(new ActionApprove($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);

        $this->activeRecordModel = Response::findOne($response_id);
        $this->activeRecordModel->status = Response::STATUS_ACCEPTED;

        $this->task->status = Task::STATUS_IN_WORK;
        $this->task->executor_id = $this->activeRecordModel->executor_id;
    }

    public function actionReview($user_id, $reviewForm)
    {
        $this->actionCheckRights(new ActionExecute($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);

        $this->activeRecordModel = new Review();
        $this->activeRecordModel->executor_id = $this->task->executor_id;
        $this->activeRecordModel->customer_id = $user_id;
        $reviewForm->loadToReviewModel($this->activeRecordModel);
        $this->task->status = Task::STATUS_EXECUTED;
    }

    public function actionResponse($user_id, $responseForm)
    {
        $this->actionCheckRights(new ActionAccept($this->task->customer_id, $this->task->executor_id, $this->task->id), $user_id);

        $this->activeRecordModel = new Response();
        $this->activeRecordModel->customer_id = $this->task->customer_id;
        $this->activeRecordModel->executor_id = $user_id;
        $responseForm->loadToResponseModel($this->activeRecordModel);
    }

    public function actionRefuse($response_id, $user_id)
    {
        $this->activeRecordModel = Response::findOne($response_id);

        $this->actionCheckRights(new ActionRefuse($this->activeRecordModel->customer_id, $this->activeRecordModel->executor_id, $this->activeRecordModel->task_id), $user_id);

        $this->activeRecordModel->status = Response::STATUS_CANCELED;
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

    public function saveAnswerChanges()
    {
        if (!$this->activeRecordModel->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function saveTaskChanges()
    {
        if (!$this->task->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function saveTransaction()
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        try {
            if ($this->task->save() && $this->activeRecordModel->save()) {
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