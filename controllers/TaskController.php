<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\forms\AddTaskForm;
use app\models\forms\FilterForm;
use app\models\forms\ResponseForm;
use app\models\forms\ReviewForm;
use app\models\Response;
use app\models\Review;
use app\models\Task;
use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionApprove;
use TaskForce\actions\ActionCancel;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionRefuse;
use TaskForce\actions\ActionReject;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends SecuredController
{
    public function actionIndex()
    {
        $filterForm = new FilterForm();
        $tasks = $filterForm->getTasksQuery()->all();

        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());
            if (!$filterForm->validate()) {
                $errors = $this->getErrors();
            } else {
                $tasks = $filterForm->getFilteredTasks();
            }
        }

        return $this->render('task', ['tasks' => $tasks, 'model' => $filterForm]);
    }

    public function actionView($id)
    {
        $task = Task::findOne($id);
        $responseForm = new ResponseForm();
        $reviewForm = new ReviewForm();

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }
        return $this->render('view', ['task' => $task, 'responseForm' => $responseForm, 'reviewForm' => $reviewForm]);
    }

    public function actionAdd()
    {
        $addTaskForm = new AddTaskForm();
        if (Yii::$app->request->getIsPost()) {
            $addTaskForm->load(Yii::$app->request->post());
            $addTaskForm->file = UploadedFile::getInstance($addTaskForm, 'file');
            if ($addTaskForm->validate()) {
                if (!$addTaskForm->loadToTask()->save()) {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }
                return $this->goHome();
            }
        }

        return $this->render('add', ['model' => $addTaskForm]);
    }

    public function actionApprove($id, $executor_id, $response_id)
    {
        $task = Task::findOne($id);
        $actionApprove = new ActionApprove($task->customer_id, $task->executor_id, $task->id);

        if ($actionApprove->rightsCheck(Yii::$app->user->id)) {
            $task->status = Task::STATUS_IN_WORK;
            $task->executor_id = $executor_id;

            $response = Response::findOne($response_id);
            $response->status = Response::STATUS_ACCEPTED;

            $transaction = Yii::$app->db->beginTransaction();

            if ($task->save() && $response->save()) {
                $transaction->commit();

                return Yii::$app->response->redirect(['task/view', 'id' => $id]);
            }
            $transaction->rollback();
            throw new ModelSaveException('Не удалось сохранить данные');
        }
    }

    public function actionReject($id)
    {
        $task = Task::findOne($id);
        $actionReject = new ActionReject($task->customer_id, $task->executor_id, $task->id);

        if ($actionReject->rightsCheck(Yii::$app->user->id)) {
            $task->status = task::STATUS_CANCELED;
            if (!$task->save()) {
                throw new ModelSaveException('Не удалось сохранить данные');
            }

            return $this->goHome();
        }
    }

    public function actionResponse()
    {
        $responseForm = new ResponseForm();
        $responseForm->load(Yii::$app->request->post());

        if ($responseForm->validate()) {
            $task = Task::findOne($responseForm->taskId);
            $actionAccept = new ActionAccept($task->customer_id, $task->executor_id, $task->id);

            if ($actionAccept->rightsCheck(Yii::$app->user->id)) {
                $response = new Response();
                $response->customer_id = $task->customer_id;
                $response->executor_id = Yii::$app->user->id;
                $responseForm->loadToResponseModel($response);

                if (!$response->save()) {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }

                return Yii::$app->response->redirect(['task/view', 'id' => $task->id]);
            }
        }
    }

    public function actionReview()
    {
        $reviewForm = new ReviewForm();
        $reviewForm->load(Yii::$app->request->post());

        if ($reviewForm->validate()) {
            $task = Task::findOne($reviewForm->taskId);
            $actionExecute = new ActionExecute($task->customer_id, $task->executor_id, $task->id);

            if ($actionExecute->rightsCheck(Yii::$app->user->id)) {
                $review = new Review();
                $review->executor_id = $task->executor_id;
                $review->customer_id = Yii::$app->user->id;
                $reviewForm->loadToReviewModel($review);
                $task->status = Task::STATUS_EXECUTED;

                $transaction = Yii::$app->db->beginTransaction();

                if ($review->save() && $task->save()) {
                    $transaction->commit();

                    return Yii::$app->response->redirect(['task']);
                }
                $transaction->rollback();
                throw new ModelSaveException('Не удалось сохранить данные');
            }
        }
    }

    public function actionRefuse($id, $response_id)
    {
        $response = Response::findOne($response_id);
        $actionRefuse = new ActionRefuse($response->customer_id, $response->executor_id, $response->task_id);

        if ($actionRefuse->rightsCheck(Yii::$app->user->id)) {
            $response->status = Response::STATUS_CANCELED;
            if (!$response->save()) {
                throw new ModelSaveException('Не удалось сохранить данные');
            }

            return Yii::$app->response->redirect(['task/view', 'id' => $id]);
        }
    }

    public function actionCancel($id)
    {
        $task = Task::findOne($id);
        $actionCancel = new ActionCancel($task->customer_id, $task->executor_id, $task->id);

        if ($actionCancel->rightsCheck(Yii::$app->user->id)) {
            $task->status = task::STATUS_FAILED;
            if (!$task->save()) {
                throw new ModelSaveException('Не удалось сохранить данные');
            }

            return $this->goHome();
        }
    }

}