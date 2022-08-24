<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex() {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $tasks_array = $activeQuery->all();
        return $this->render('task', ['tasks_array' => $tasks_array]);
    }

}