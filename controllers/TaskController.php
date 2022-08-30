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
        $filterForm = new FilterForm();
        $tasks = $filterForm->getFilteredTasks();

        return $this->render('task', ['tasks' => $tasks, 'model' => $filterForm]);
    }
}