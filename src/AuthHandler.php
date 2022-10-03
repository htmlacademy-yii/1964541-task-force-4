<?php

namespace TaskForce;

use app\models\Auth;
use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\helpers\ArrayHelper;

class AuthHandler
{
    public $attributes;
    public $vk;
    public $auth;

    /**
     * Создает помошника для работы с VK API
     * @param string $code Полученный от VK API код
     * @throws \yii\web\HttpException
     */
    public function __construct($code)
    {
        $this->vk = new VKontakte();
        $this->vk->clientId = '51433678';
        $this->vk->clientSecret = 'RMSSSU8DaKlSaLw7Gsj6';
        $this->vk->setReturnUrl('http://localhost:8080/login/vk');
        $accessToken = $this->vk->fetchAccessToken($code);
        $this->attributes = $this->vk->getUserAttributes();
        $this->attributes['email'] = ArrayHelper::getValue($accessToken->params, 'email');
    }

    /**
     * Проверяет существование пользователя, если есть, сохраняет
     * @return bool Пользователь есть|нет
     */
    public function isAuthExist()
    {
        $this->auth = Auth::find()->where([
            'source' => $this->vk->getId(),
            'source_id' => $this->attributes['id'],
        ])->one();

        if ($this->auth) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed Пользователь
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Сохраняет User и Auth
     * @return bool|void
     * @throws ModelSaveException Сохранение не удалось
     * @throws \yii\db\Exception Транзакция не удалась
     */
    public function saveAuthUser()
    {
        $user = new User();
        $user->loadAuthUser($this->attributes);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($user->save()) {
                $this->auth = new Auth([
                    'user_id' => $user->id,
                    'source' => $this->vk->getId(),
                    'source_id' => (string)$this->attributes['id'],
                ]);
                if ($this->auth->save()) {
                    $transaction->commit();
                    return true;
                } else {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }
            }
        } catch (ModelSaveException $exception) {
            $transaction->rollback();
            throw new ModelSaveException($exception->getMessage());
        }
    }
}