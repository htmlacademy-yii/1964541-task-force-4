<?php

namespace app\models\forms;

use app\models\City;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $password;
    public $passwordRepeat;
    public $login;
    public $city_id;
    public $user_type;

    public function rules()
    {
        return [
            [['email', 'password', 'passwordRepeat', 'login'], 'required'],
            [['email', 'login'], 'string', 'max' => 320],
            [['password', 'passwordRepeat'], 'string', 'max' => 64],
            [['passwordRepeat'], 'compare'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['login'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
            'login' => 'Ваше имя',
            'user_type' => 'Я собираюсь откликаться на заказы',
            'city_id' => 'Город',
        ];
    }
}