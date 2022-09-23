<?php
namespace TaskForce;

use app\models\Task;
use yii\db\Expression;

class MyTaskFilter
{
    public $type;
    public $userId;

    public function __construct(string $type, int $userId)
    {
        $this->userId =$userId;
        $this->type = $type;
    }

    public function getFilteredCustomerTasks()
    {
        $taskQuery = Task::find()
            ->joinWith('city')
            ->andFilterWhere(['customer_id' => $this->userId]);

        switch ($this->type) {
            case Task::STATUS_NEW:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_NEW]);
                break;
            case Task::STATUS_IN_WORK:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_IN_WORK]);
                break;
            case 'closed':
                $taskQuery->orFilterWhere(['status' => Task::STATUS_EXECUTED])->orFilterWhere(['status' => Task::STATUS_CANCELED])->orFilterWhere(['status' => Task::STATUS_FAILED]);
                break;
        }
        return $taskQuery->all();
    }

    public function getFilteredExecutorTasks()
    {
        $taskQuery = Task::find()
            ->joinWith('city')
            ->andFilterWhere(['executor_id' => $this->userId]);

        switch ($this->type) {
            case Task::STATUS_NEW:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_NEW]);
                break;
            case 'overdue':
                $taskQuery->andFilterWhere(['status' => Task::STATUS_IN_WORK])->andFilterWhere(['<', 'deadline', new Expression('NOW()')]);
                break;
            case 'closed':
                $taskQuery->orFilterWhere(['status' => Task::STATUS_EXECUTED])->orFilterWhere(['status' => Task::STATUS_FAILED]);
                break;
        }
        return $taskQuery->all();
    }
}