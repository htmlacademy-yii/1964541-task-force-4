<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TaskController extends Controller
{
    public function actionIndex() {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['status'=> Task::STATUS_NEW]);
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);
        $tasks = $activeQuery->all();
        return $this->render('task', ['tasks' => $tasks]);
    }

    public function actionView($id) {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['task.id' => $id]);
        $task = $activeQuery->one();
        if (!$task) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }
        return $this->render('view', ['task' => $task]);
    }

}