<?php

namespace app\models\forms;

use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\base\Model;

class PasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $repeatPassword;

    const PASSWORD_MAX_LENGTH = 64;

    /**
     * Возвращает массив правил валидации
     * @return array
     */
    public function rules(): array
    {
        return [
            [['oldPassword', 'newPassword', 'repeatPassword', 'userId'], 'required'],
            [['newPassword', 'repeatPassword'], 'string', 'max' => self::PASSWORD_MAX_LENGTH],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['oldPassword'], 'validatePassword']
        ];
    }

    /**
     * Возвращает массив лейблов для аттрибутов
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'repeatPassword' => 'Повторите пароль'
        ];
    }

    /**
     * Сравнивает пароль введенный пользователем с хэшем пароля, хранящимся в БД
     * @param $attribute
     * @return void
     */
    public function validatePassword($attribute): void
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['id' => Yii::$app->user->id]);;
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    /**
     * Грузит хэш нового пароля в БД
     * @return void
     * @throws ModelSaveException Сохранение модели пользователя не удалось
     * @throws \yii\base\Exception Не удалось сгенерировать хэш
     */
    public function loadToUser(): void
    {
        $user = User::findOne(Yii::$app->user->id);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPassword);

        if (!$user->save()) {
            throw new ModelSaveException('Не удалось сохранить модель User');
        }
    }
}