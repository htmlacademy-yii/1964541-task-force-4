<?php

namespace app\models\forms;

use app\models\City;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

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
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email']],
            [['login'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['login' => 'login']],
            [['user_type'], 'boolean'],
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

    public function loadToUser()
    {
        $user = new User;
        $user->email = $this->email;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->login = $this->login;
        $user->city_id = $this->city_id;
        $user->user_type = $this->user_type == 1 ? 'executor' : 'customer';
        $user->save();
    }
}