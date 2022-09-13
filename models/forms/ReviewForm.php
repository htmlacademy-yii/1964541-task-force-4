<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class ReviewForm extends Model
{
    public $content;
    public $taskId;
    public $customerId;
    public $executorId;
    public $grade;

    public function rules()
    {
        return [
            [['taskId', 'executorId', 'customerId', 'content'], 'required'],
            [['grade'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Ваш комментарий',
            'price' => 'Оценка работы',
        ];
    }

    public function getIdsData($task)
    {
        $this->taskId = $task->id;
        $this->customerId = $task->customer_id;
        $this->executorId = Yii::$app->user->id;
    }
}