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
    public $cityId;
    public $isExecutor;

    /**
     * Возвращает массив правил валидации
     * @return array
     */
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
            [['isExecutor'], 'boolean'],
            [['cityId'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['cityId' => 'id']],
        ];
    }

    /**
     * Возвращает массив лейблов для аттрибутов
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Пароль',
            'passwordRepeat' => 'Повтор пароля',
            'login' => 'Ваше имя',
            'isExecutor' => 'Я собираюсь откликаться на заказы',
            'cityId' => 'Город',
        ];
    }

    /**
     * Создает объект User и грузит в него данные из формы
     * @return User Новый юзер
     * @throws \yii\base\Exception Ошибка создания хэша пароля
     */
    public function loadToUser()
    {
        $user = new User;
        $user->email = $this->email;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $user->login = $this->login;
        $user->city_id = $this->cityId;
        $user->user_type = $this->isExecutor == 1 ? User::EXECUTOR_STATUS : User::CUSTOMER_STATUS;

        return $user;
    }
}