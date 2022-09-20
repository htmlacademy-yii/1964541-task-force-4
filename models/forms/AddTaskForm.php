<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use GuzzleHttp\Client;
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
    public $file;
    public $filePath;
    public $address;
    private $location;
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
            'file' => 'Файлы',
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category', 'price', 'address'], 'required'],
            [['title'], 'string', 'length' => [self::TITLE_MIN_LENGTH, self::TITLE_MAX_LENGTH]],
            [['description'], 'string', 'length' => [self::DESCRIPTION_MIN_LENGTH]],
            [['deadline'], 'date', 'format' => 'php:Y-m-d'],
            [['category'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']],
            [['file'], 'file'],
            [['price'], 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            [['deadline'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>', 'type' => 'date', 'message' => 'Срок исполнения не может быть раньше текущей даты']
        ];
    }

    private function uploadFile()
    {
        if ($this->file && $this->validate()) {
            $newName = uniqid('upload') . '.' . $this->file->getExtension();
            $this->file->saveAs('@webroot/uploads/' . $newName);

            $this->filePath = $newName;
            return true;
        }
        return false;
    }

    public function getLocation()
    {
        $client = new Client(['base_uri' => 'https://geocode-maps.yandex.ru/']);
        $response = $client->request('GET', '1.x', ['query' => ['apikey' => 'e666f398-c983-4bde-8f14-e3fec900592a', 'geocode' => $this->address, 'format' => 'json']]);
        $content = $response->getBody()->getContents();
        $responseData = json_decode($content, true);

        $this->location = explode(' ', ArrayHelper::getValue($responseData, 'response.GeoObjectCollection.featureMember.0.GeoObject.Point.pos'));
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
        $task->address = $this->address;
        $task->long = $this->location[0];
        $task->lat = $this->location[1];

        return $task;
    }
}