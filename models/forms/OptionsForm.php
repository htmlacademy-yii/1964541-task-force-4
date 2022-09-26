<?php

namespace app\models\forms;

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
}