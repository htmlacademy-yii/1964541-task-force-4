<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\Files;
use app\models\forms\AddTaskForm;
use app\models\forms\FilterForm;
use app\models\forms\ResponseForm;
use app\models\forms\ReviewForm;
use app\models\Task;
use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use TaskForce\MyTaskFilter;
use TaskForce\TaskService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'matchCallback' => function ($rule, $action) {
                    return Yii::$app->user->identity->user_type === USER::EXECUTOR_STATUS;
            }];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex()
    {
        $filterForm = new FilterForm();
        $tasksDataProvider = $filterForm->getFilteredTasksData();

        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());
            if (!$filterForm->validate()) {
                $errors = $this->getErrors();
            } else {
                $tasksDataProvider = $filterForm->getFilteredTasksData();
            }
        }

        return $this->render('task', ['tasksDataProvider' => $tasksDataProvider, 'model' => $filterForm]);
    }

    public function actionFile($fileName)
    {
        $currentFile = Yii::$app->basePath . '/web/uploads/' . $fileName;

        if (!is_file($currentFile)) {
            throw new NotFoundHttpException('Файл не найден');
        }
        Yii::$app->response->sendFile($currentFile)->send();

        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionMy($type)
    {
        $taskFilter = new MyTaskFilter($type, Yii::$app->user->id);

        if ($taskFilter->isCustomer()) {
            $tasks = $taskFilter->getFilteredCustomerTasks();
        }

        if ($taskFilter->isExecutor()) {
            $tasks = $taskFilter->getFilteredExecutorTasks();
        }

        return $this->render('my', ['tasks' => $tasks]);
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
            $addTaskForm->files = UploadedFile::getInstances($addTaskForm, 'files');
            if ($addTaskForm->validate()) {

                $addTaskForm->loadToTask();

                return $this->goHome();
            }
        }

        return $this->render('add', ['model' => $addTaskForm]);
    }

    public function actionApprove($id, $response_id)
    {
        $taskService = new TaskService($id, Yii::$app->user->id);
        $taskService->actionApprove($response_id);

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionReject($id)
    {
        $taskService = new TaskService($id, Yii::$app->user->id);
        $taskService->actionReject();

        return $this->goHome();
    }

    public function actionResponse()
    {
        $responseForm = new ResponseForm();
        $responseForm->load(Yii::$app->request->post());

        if ($responseForm->validate()) {
            $taskService = new TaskService($responseForm->taskId, Yii::$app->user->id);
            $taskService->actionResponse($responseForm);

            return Yii::$app->response->redirect(['task/view', 'id' => $responseForm->taskId]);
        }
    }

    public function actionReview()
    {
        $reviewForm = new ReviewForm();
        $reviewForm->load(Yii::$app->request->post());

        if ($reviewForm->validate()) {
            $taskService = new TaskService($reviewForm->taskId, Yii::$app->user->id);
            $taskService->actionReview($reviewForm);

            return Yii::$app->response->redirect(['task']);
        }
    }

    public function actionRefuse($id, $response_id)
    {
        $taskService = new TaskService($id, Yii::$app->user->id);
        $taskService->actionRefuse($response_id);

        return Yii::$app->response->redirect(['task/view', 'id' => $id]);
    }

    public function actionCancel($id)
    {
        $taskService = new TaskService($id, Yii::$app->user->id);
        $taskService->actionCancel();

        return $this->goHome();
    }
}