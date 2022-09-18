<?php

namespace app\models\forms;

use app\models\Review;
use app\models\Task;
use Yii;
use yii\base\Model;

class ReviewForm extends Model
{
    public $content;
    public $taskId;
    public $grade;

    public function rules()
    {
        return [
            [['taskId', 'grade', 'content'], 'required'],
            [['taskId'], 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['taskId' => 'id']],
            [['grade'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [['grade'], 'compare', 'compareValue' => 5, 'operator' => '<=', 'type' => 'number']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Ваш комментарий',
            'price' => 'Оценка работы',
        ];
    }

    public function loadToReviewModel($review)
    {
        $review->task_id = $this->taskId;
        $review->grade = $this->grade;
        $review->content = $this->content;

        return $review;
    }
}