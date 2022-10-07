<?php
namespace TaskForce;

use app\models\Task;
use app\models\User;
use TaskForce\exceptions\BadRequestException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

class MyTaskFilter
{
    public string $type;
    public int $userId;

    public function __construct(string $type, int $userId)
    {
        $this->userId =$userId;
        if ($type === Task::STATUS_IN_WORK || $type === Task::STATUS_OVERDUE || $type === Task::STATUS_CLOSED || $type === Task::STATUS_NEW) {
            $this->type = $type;
        } else {
            throw new BadRequestException('Данной катеогории не существует');
        }
    }

    public function isExecutor(): bool
    {
        if (Yii::$app->user->identity->user_type === User::EXECUTOR_STATUS) {
            return true;
        }

        return false;
    }

    public function isCustomer(): bool
    {
        if (Yii::$app->user->identity->user_type === User::CUSTOMER_STATUS) {
            return true;
        }

        return false;
    }

    public function getFilteredCustomerTasks(): array
    {
        $taskQuery = Task::find()
            ->joinWith('city')
            ->andFilterWhere(['customer_id' => $this->userId]);

        switch ($this->type) {
            case Task::STATUS_NEW:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_NEW])->andFilterWhere(['executor_id' => null]);
                break;
            case Task::STATUS_IN_WORK:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_IN_WORK]);
                break;
            case Task::STATUS_CLOSED:
                $taskQuery->andFilterWhere(['in', 'status', [Task::STATUS_EXECUTED, Task::STATUS_CANCELED, Task::STATUS_FAILED]]);
                break;
        }
        return $taskQuery->all();
    }

    public function getFilteredExecutorTasks(): array
    {
        $taskQuery = Task::find()
            ->joinWith('city')
            ->andFilterWhere(['executor_id' => $this->userId]);

        switch ($this->type) {
            case Task::STATUS_IN_WORK:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_IN_WORK]);
                break;
            case Task::STATUS_OVERDUE:
                $taskQuery->andFilterWhere(['status' => Task::STATUS_IN_WORK])->andFilterWhere(['<', 'deadline', new Expression('NOW()')]);
                break;
            case Task::STATUS_CLOSED:
                $taskQuery->andFilterWhere(['in', 'status', [Task::STATUS_EXECUTED, Task::STATUS_FAILED]]);
                break;
        }
        return $taskQuery->all();
    }
}