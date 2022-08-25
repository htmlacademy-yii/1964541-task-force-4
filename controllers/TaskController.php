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
        $activeQuery->where(['status'=> Task::STATUS_NEW]);
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);
        $taskArray = $activeQuery->all();
        return $this->render('task', ['tasks_array' => $taskArray]);
    }

}