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
        $filterForm = new FilterForm();
        if (Yii::$app->request->getIsPost()) {
            $filterForm->load(Yii::$app->request->post());
            if (!$filterForm->validate()) {
                $errors = $filterForm->getErrors();
            }
            if (isset($filterForm->category)) {
                $activeQuery->andFilterWhere(['category.id' => $filterForm->category]);
            }
            if ($filterForm->noExecutor) {
                $activeQuery->andWhere(['executor_id' => null]);
            }
            if ($filterForm->period) {
                switch ($filterForm->period) {
                    case FilterForm::ONE_HOUR:
                        $activeQuery->andFilterWhere(['between', 'deadline', 'NOW', 'NOW + 1 hour']);
                        break;
                    case FilterForm::TWELVE_HOURS:
                        $activeQuery->andFilterWhere(['between', 'deadline', 'NOW', 'NOW + 12 hours']);
                        break;
                    case FilterForm::TWENTY_FOUR_HOURS:
                        $activeQuery->andFilterWhere(['between', 'deadline', 'NOW', 'NOW + 24 hours']);
                        break;
                }
            }
        }
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);
        $tasks = $activeQuery->all();
        return $this->render('task', ['tasks' => $tasks, 'model' => $filterForm]);
    }
}