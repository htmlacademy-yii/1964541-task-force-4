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

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'repeatPassword' => 'Повторите пароль'
        ];
    }

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatPassword', 'userId'], 'required'],
            [['newPassword', 'repeatPassword'], 'string', 'max' => self::PASSWORD_MAX_LENGTH],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['oldPassword'], 'validatePassword']
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['id' => Yii::$app->user->id]);;
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    public function loadToUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPassword);

        if (!$user->save()) {
            throw new ModelSaveException('Не удалось сохранить модель User');
        }
    }
}