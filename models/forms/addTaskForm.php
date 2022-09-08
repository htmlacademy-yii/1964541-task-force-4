<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use TaskForce\exceptions\FileUploadException;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\base\Model;

class addTaskForm extends Model
{
    public $title;
    public $description;
    public $category;
    public $price;
    public $deadline;
    public $file;
    public $filePath;

    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть проблемы',
            'description' => 'Подробности задания',
            'category' => 'Категория',
            'price' => 'Бюджет',
            'deadline' => 'Срок исполнения',
            'file' => 'Файлы',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category', 'price'], 'required'],
            [['title'], 'string', 'length' => ['10', '128']],
            [['description'], 'string', 'length' => ['30']],
            [['deadline'], 'date', 'format' => 'php:Y-m-d'],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            [['file'], 'file'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number']
        ];
    }

    private function uploadFile()
    {
        if ($this->file && $this->validate()) {
            $newname = uniqid('upload') . '.' . $this->file->getExtension();
            $this->file->saveAs('@webroot/uploads/' . $newname);

            $this->filePath = $newname;
            return true;
        }
        return false;
    }

    public function loadToTask()
    {
        if (!$this->uploadFile() && $this->file) {
            throw new FileUploadException('Загрузить файл не удалось');
        }

        $task = new Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category;
        $task->city_id = Yii::$app->user->identity->city_id;
        $task->price = $this->price;
        $task->customer_id = Yii::$app->user->id;
        $task->deadline = $this->deadline;
        $task->file = $this->filePath;
        $task->status = Task::STATUS_NEW;

        return $task;
    }
}