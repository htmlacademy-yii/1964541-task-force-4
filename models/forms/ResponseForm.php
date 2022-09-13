<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

class ResponseForm extends Model
{
    public $content;
    public $price;
    public $taskId;
    public $customerId;
    public $executorId;

    public function rules()
    {
        return [
            [['taskId', 'executorId', 'customerId', 'content'], 'required'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Ваш комментарий',
            'price' => 'Стоимость',
        ];
    }

    public function getIdsData($task)
    {
        $this->taskId = $task->id;
        $this->customerId = $task->customer_id;
        $this->executorId = Yii::$app->user->id;
    }
}