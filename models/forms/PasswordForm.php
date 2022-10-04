<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class PasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $repeatPassword;
    public $userId;

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
            [['newPassword', 'repeatPassword'], 'string', 'max' => 64],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'newPassword'],
            [['oldPassword'], 'validatePassword']
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(['id' => $this->userId]);;
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }

    public function loadToUser()
    {
        $user = User::findOne($this->userId);
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPassword);

        return $user;
    }
}