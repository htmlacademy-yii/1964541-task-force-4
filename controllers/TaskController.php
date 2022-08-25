<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex() {
        $active_query = Task::find();
        $active_query->joinWith('city');
        $active_query->joinWith('category');
        $active_query->where(['status'=> Task::STATUS_NEW]);
        $active_query->orderBy(['dt_add' => SORT_ASC]);
        $tasks_array = $active_query->all();
        return $this->render('task', ['tasks_array' => $tasks_array]);
    }

}