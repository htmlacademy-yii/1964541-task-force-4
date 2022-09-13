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

        if (Yii::$app->request->getIsPost() && $task->customer_id === Yii::$app->user->id) {
            $reviewForm->load(Yii::$app->request->post());
            $reviewForm->getIdsData($task);

            if ($reviewForm->validate()) {
                $review = new Review();
                $review->loadForm($reviewForm);
                if ($review->save()) {
                    $task->status = Task::STATUS_EXECUTED;
                }
            }
        }

        if (Yii::$app->request->getIsPost()) {
            $responseForm->load(Yii::$app->request->post());
            $responseForm->getIdsData($task);

            if ($responseForm->validate()) {
                $response = new Response();
                $response->loadForm($responseForm);
                $response->save();
            }
        }

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

    public function actionApprove($id, $executor_id, $response_id) #Заказчик назначает исполнителя на работу
    {
        $task = Task::findOne($id);
        $task->status = task::STATUS_IN_WORK;
        $task->executor_id = $executor_id;
        $task->save();

        $response = Response::findOne($response_id);
        $response->status = Response::STATUS_ACCEPTED;
        $response->save();

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionReject($id) #Заказчик отменяет заказ
    {
        $task = Task::findOne($id);
        $task->status = task::STATUS_CANCELED;
        $task->save();

        return $this->goHome();
    }

    public function actionExecute()
    {

    }

    public function actionRefuse($id, $response_id) #Заказчик отказывает исполнителю
    {
        $response = Response::findOne($response_id);
        $response->status = Response::STATUS_CANCELED;
        $response->save();

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionCancel($id) #Исполнитель отказывается от задания
    {
        $task = Task::findOne($id);
        $task->status = task::STATUS_FAILED;
        $task->save();

        return $this->goHome();
    }

}