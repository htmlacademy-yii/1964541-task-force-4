<?php

namespace app\models\forms;

use app\models\Task;
use Yii;
use yii\base\Model;

class ResponseForm extends Model
{
    public $content;
    public $price;
    public $taskId;


    public function rules()
    {
        return [
            [['taskId', 'price', 'content'], 'required'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [['taskId'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['taskId' => 'id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Ваш комментарий',
            'price' => 'Стоимость',
        ];
    }
}