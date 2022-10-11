<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Files;
use app\models\Task;
use GuzzleHttp\Client;
use TaskForce\AddressTransformer;
use TaskForce\exceptions\FileUploadException;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AddTaskForm extends Model
{
    public $title;
    public $description;
    public $category;
    public $price;
    public $deadline;
    public $files;
    public $filePaths;
    public $address;
    const TITLE_MIN_LENGTH = 10;
    const TITLE_MAX_LENGTH = 128;
    const DESCRIPTION_MIN_LENGTH = 30;

    public function attributeLabels()
    {
        return [
            'title' => 'Опишите суть проблемы',
            'description' => 'Подробности задания',
            'category' => 'Категория',
            'price' => 'Бюджет',
            'address' => 'Локация',
            'deadline' => 'Срок исполнения',
            'files' => 'Файлы',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category', 'price'], 'required'],
            [['address'], 'string'],
            [['title'], 'string', 'length' => [self::TITLE_MIN_LENGTH, self::TITLE_MAX_LENGTH]],
            [['description'], 'string', 'length' => [self::DESCRIPTION_MIN_LENGTH]],
            [['deadline'], 'date', 'format' => 'php:Y-m-d'],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            [['files'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 4, 'checkExtensionByMimeType' => false],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [
                ['deadline'],
                'compare',
                'compareValue' => date('Y-m-d'),
                'operator' => '>',
                'type' => 'date',
                'message' => 'Срок исполнения не может быть раньше текущей даты'
            ]
        ];
    }

    private function uploadFiles(): bool
    {
        if ($this->files && $this->validate()) {
            foreach ($this->files as $file) {
                $newName = uniqid('upload') . '.' . $file->getExtension();
                $file->saveAs('@webroot/uploads/' . $newName);
                $this->filePaths[] = $newName;
            }
            return true;
        }
        return false;
    }

    private function loadLocation(Task $task): void
    {
        $task->lat = Yii::$app->geocoder->getLat($this->address, Yii::$app->user->identity->city->name);
        $task->long = Yii::$app->geocoder->getLong($this->address, Yii::$app->user->identity->city->name);
        $task->city_id = Yii::$app->user->identity->city_id;
    }

    public function loadToTask(): void
    {
        $task = new Task();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category;
        $task->price = $this->price;
        $task->customer_id = Yii::$app->user->id;
        $task->deadline = $this->deadline;
        $task->status = Task::STATUS_NEW;

        if ($this->address) {
            $this->loadLocation($task);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$task->save()) {
                throw new ModelSaveException('Не удалось сохранить задание');
            }

            if ($this->files) {
                $this->saveFiles($task);
            }

            $transaction->commit();
        } catch (ModelSaveException $exception)
        {
            $transaction->rollback();
            throw new ModelSaveException($exception->getMessage());
        }
    }

    private function saveFiles(Task $task): void
    {
        if ($this->uploadFiles()) {
            foreach ($this->filePaths as $filePath) {
                $files = new Files();
                $files->task_id = $task->id;
                $files->file = $filePath;
                if (!$files->save()) {
                    throw new ModelSaveException('Не удалось сохранить файлы');
                };
            }
        }
    }


}