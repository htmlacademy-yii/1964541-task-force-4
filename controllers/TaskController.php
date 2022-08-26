<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex() {
        if (Yii::$app->request->getIsPost()) {
            $filterForm = Yii::$app->request->post();
        }
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['status'=> Task::STATUS_NEW]);
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);
        $tasks = $activeQuery->all();
        return $this->render('task', ['tasks' => $tasks]);
    }

}