<?php

namespace app\controllers;

use app\models\forms\FilterForm;
use app\models\Task;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }
        return $this->render('view', ['task' => $task]);
    }
}