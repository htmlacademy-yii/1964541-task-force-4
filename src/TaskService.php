<?php

namespace TaskForce;

use app\models\forms\ResponseForm;
use app\models\forms\ReviewForm;
use app\models\Response;
use app\models\Review;
use app\models\Task;
use TaskForce\actions\ActionAbstract;
use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionApprove;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use TaskForce\actions\ActionReject;
use TaskForce\exceptions\ActionUnavailableException;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\db\ActiveRecord;

class TaskService
{
    private Task $task;
    private int $userId;

    public function __construct(int $taskId, int $userId)
    {
        $this->task = Task::findOne($taskId);
        $this->userId = $userId;
    }

    /**
     * Назначает исполнителя на заказ
     * @param int $response_id Id отклика на задание
     * @return void
     * @throws ActionUnavailableException|\yii\db\Exception Пользователь не прошел проверку прав|Транзакция не удалась
     */
    public function actionApprove(int $response_id)
    {
        $this->actionCheckRights(
            new ActionApprove($this->task->customer_id, $this->task->executor_id, $this->task->id)
        );

        $response = Response::findOne($response_id);
        $response->status = Response::STATUS_ACCEPTED;

        $this->task->status = Task::STATUS_IN_WORK;
        $this->task->executor_id = $response->executor_id;

        $this->saveTransaction($this->task, $response);
    }

    /**
     * Завершает задание и сохраняет отзыв о его выполнении
     * @param ReviewForm $reviewForm Форма отзыва на выполнение задания
     * @return void
     * @throws ActionUnavailableException|\yii\db\Exception Пользователь не прошел проверку прав|Транзакция не удалась
     */
    public function actionReview(ReviewForm $reviewForm)
    {
        $this->actionCheckRights(
            new ActionExecute($this->task->customer_id, $this->task->executor_id, $this->task->id)
        );

        $review = new Review();
        $review->executor_id = $this->task->executor_id;
        $review->customer_id = $this->userId;
        $review->task_id = $this->task->id;
        $review->grade = $reviewForm->grade;
        $review->content = $reviewForm->content;
        $this->task->status = Task::STATUS_EXECUTED;

        $this->saveTransaction($this->task, $review);
    }

    /**
     * Принимает задание и сохраняет отклик
     * @param ResponseForm $responseForm Форма отклика на задание
     * @return void
     * @throws ActionUnavailableException Пользователь не прошел проверку прав
     * @throws ModelSaveException Сохранение модели не удалось
     */
    public function actionResponse(ResponseForm $responseForm)
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

    /**
     * Отклоняет отклик исполнителя
     * @param int $response_id Id исполнителя
     * @return void
     * @throws ActionUnavailableException Пользователь не прошел проверку прав
     * @throws ModelSaveException Сохранение модели не удалось
     */
    public function actionRefuse(int $response_id)
    {
        $response = Response::findOne($response_id);

        $this->actionCheckRights(new ActionRefuse($response->customer_id, $response->executor_id, $response->task_id));

        $response->status = Response::STATUS_CANCELED;

        $this->saveChanges($response);
    }

    /**
     * Исполнитель отказывается от выполнения уже принятого задания
     * @return void
     * @throws ActionUnavailableException Пользователь не прошел проверку прав
     * @throws ModelSaveException Сохранение модели не удалось
     */
    public function actionCancel()
    {
        $this->actionCheckRights(new ActionCancel($this->task->customer_id, $this->task->executor_id, $this->task->id));
        $this->task->status = Task::STATUS_FAILED;

        $this->saveChanges($this->task);
    }

    /**
     * Заказчик отменяет заказ
     * @return void
     * @throws ActionUnavailableException Пользователь не прошел проверку прав
     * @throws ModelSaveException Сохранение модели не удалось
     */
    public function actionReject()
    {
        $this->actionCheckRights(new ActionReject($this->task->customer_id, $this->task->executor_id, $this->task->id));
        $this->task->status = Task::STATUS_CANCELED;

        $this->saveChanges($this->task);
    }

    /**
     * Сохраняет изменения внутри объектов
     * @param Response|Task $object Принимает объект, который требуется сохранить
     * @return void
     * @throws ModelSaveException Сохранение модели не удалось
     */
    private function saveChanges(Response|Task $object)
    {
        if (!$object->save()) {
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    /**
     * Сохраняет данные в задании и форме
     * @param Task $task Задание, которое участвует в действии
     * @param Response|Review $form Форма отклика или отзыва
     * @return void
     * @throws \yii\db\Exception Не удалось провести транзакцию
     */
    private function saveTransaction(Task $task, ActiveRecord $form)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$task->save()) {
                throw new ModelSaveException('Не удалось сохранить данные');
            }
            if (!$form->save()) {
                throw new ModelSaveException('Не удалось сохранить данные');
            }
            $transaction->commit();
        } catch (ModelSaveException $exception) {
            $transaction->rollback();
            throw new ModelSaveException($exception->getMessage());
        }
    }

    /**
     * Проверяет наличие прав у пользователя на совершение действия
     * @param ActionAbstract $actionObject Объект действия
     * @return void
     * @throws ActionUnavailableException Пользователь не прошел проверку прав
     */
    private function actionCheckRights(ActionAbstract $actionObject)
    {
        if (!$actionObject->rightsCheck($this->userId)) {
            throw new ActionUnavailableException('Данное действие недоступно');
        }
    }
}