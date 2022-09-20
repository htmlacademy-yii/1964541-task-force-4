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
    private object $task;
    private object $actionObject;
    private $userId;
    private $transaction;

    public function __construct($taskId, $userId)
    {
        $this->task = Task::findOne($taskId);
        $this->userId = $userId;
    }

    public function actionApprove($response_id)
    {
        $this->actionCheckRights(new ActionApprove($this->task->customer_id, $this->task->executor_id, $this->task->id));

        $response = Response::findOne($response_id);
        $response->status = Response::STATUS_ACCEPTED;

        $this->task->status = Task::STATUS_IN_WORK;
        $this->task->executor_id = $response->executor_id;

        $this->saveTransaction($this->task, $response);
    }

    public function actionReview($reviewForm)
    {
        $this->actionCheckRights(new ActionExecute($this->task->customer_id, $this->task->executor_id, $this->task->id));

        $review = new Review();
        $review->executor_id = $this->task->executor_id;
        $review->customer_id = $this->userId;
        $review->task_id = $this->task->id;
        $review->grade = $reviewForm->grade;
        $review->content = $reviewForm->content;
        $this->task->status = Task::STATUS_EXECUTED;

        $this->saveTransaction($this->task, $review);
    }

    public function actionResponse($responseForm)
    {
        $this->actionCheckRights(new ActionAccept($this->task->customer_id, $this->task->executor_id, $this->task->id));

        $response = new Response();
        $response->customer_id = $this->task->customer_id;
        $response->executor_id = $this->userId;
        $response->task_id = $responseForm->taskId;
        $response->price = $responseForm->price;
        $response->content = $responseForm->content;

        $this->saveChanges($response);
    }

    public function actionRefuse($response_id)
    {
        $response = Response::findOne($response_id);

        $this->actionCheckRights(new ActionRefuse($response->customer_id, $response->executor_id, $response->task_id));

        $response->status = Response::STATUS_CANCELED;

        $this->saveChanges($response);
    }

    public function actionCancel()
    {
        $this->actionCheckRights(new ActionCancel($this->task->customer_id, $this->task->executor_id, $this->task->id));
        $this->task->status = task::STATUS_FAILED;

        $this->saveChanges($this->task);
    }

    public function actionReject()
    {
        $this->actionCheckRights(new ActionReject($this->task->customer_id, $this->task->executor_id, $this->task->id));
        $this->task->status = Task::STATUS_CANCELED;

        $this->saveChanges($this->task);
    }

    private function saveChanges($object)
    {
        if (!$object->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    private function saveTransaction($task, $form)
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        try {
            if ($task->save() && $form->save()) {
                $this->transaction->commit();
            }
            throw new ModelSaveException('Не удалось сохранить данные');
        } catch (ModelSaveException $exception) {
            $this->transaction->rollback();
            error_log("Не удалось записать данные. Ошибка: " . $exception->getMessage());
        }
    }

    private function actionCheckRights($actionObject)
    {
        $this->actionObject = $actionObject;
        if (!$this->actionObject->rightsCheck($this->userId)) {
            throw new ActionUnavailableException('Данное действие недоступно');
        }
    }
}