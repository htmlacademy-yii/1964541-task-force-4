<?php

namespace app\controllers;

use app\models\forms\FilterForm;
use app\models\Task;
use Yii;
use yii\db\Expression;
use yii\web\Controller;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['status' => Task::STATUS_NEW]);
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);
        $filterForm = new FilterForm();
        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());
            if (!$filterForm->validate()) {
                $errors = $filterForm->getErrors();
                $tasks = $filterForm->getFilteredTasks();
            }
        } else {
            $tasks = $activeQuery->all();
        }
        return $this->render('task', ['tasks' => $tasks, 'model' => $filterForm]);
    }
}