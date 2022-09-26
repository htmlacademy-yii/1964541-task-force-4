<?php

namespace app\models\forms;

use app\models\Category;
use app\models\User;
use TaskForce\exceptions\FileUploadException;
use yii\base\Model;
use yii\behaviors\AttributeBehavior;

class OptionsForm extends Model
{
    public $login;
    public $email;
    public $birthDate;
    public $phone;
    public $telegram;
    public $description;
    public $userCategory;
    public $file;
    private $filePath;
    const PHONE_NUM_LENGTH = 11;
    const TELEGRAM_LENGTH = 64;

    public function rules()
    {
        return [
            [['login', 'email'], 'required'],
            [['phone'], 'compare', 'operator' => '==', 'compareValue' => self::PHONE_NUM_LENGTH],
            [['telegram'], 'string', 'length' => [self::TELEGRAM_LENGTH]],
            [['birthDate'], 'date', 'format' => 'php:d.m.Y'],
            [['userCategory'], 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['userCategory' => 'id']],
            [['file'], 'file'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'login' => 'Ваше имя',
            'email' => 'Email',
            'birthDate' => 'День рождения',
            'phone' => 'Номер телефона',
            'telegram' => 'Telegram',
            'description' => 'Информация о себе',
            'userCategory' => 'Выбор специализации',
            'file' => 'Сменить аватар'
        ];
    }

    public function loadToUser()
    {
        if (!$this->uploadFile() && $this->file) {
            throw new FileUploadException('Загрузить файл не удалось');
        }

        $user = new User();
        $user->email = $this->email;
        $user->login = $this->login;
        //$user->birthDate = $this->birthDate;
        $user->phone = $this->phone;
        $user->telegram = $this->telegram;
        $user->description = $this->description;
        $user->avatar = $this->filePath;
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
}