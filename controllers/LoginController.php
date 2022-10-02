<?php

namespace app\controllers;

use app\components\AccessControllers\AnonymousController;
use app\models\Auth;
use app\models\forms\LoginForm;
use app\models\User;
use GuzzleHttp\Client;
use http\Exception\UnexpectedValueException;
use TaskForce\exceptions\BadRequestException;
use TaskForce\exceptions\WrongAnswerFormatException;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends AnonymousController
{

    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();

        if (Yii::$app->request->isPost) {
            $loginForm->load(Yii::$app->request->post());
            if ($loginForm->validate()) {
                if (!Yii::$app->request->isAjax) {
                    $user = $loginForm->getUser();
                    Yii::$app->user->login($user);

                    return $this->goHome();
                }
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($loginForm);
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

    public function actionAuth()
    {
        $vk = new VKontakte();
        $vk->clientId = '51433678';
        $vk->clientSecret = 'RMSSSU8DaKlSaLw7Gsj6';
        $vk->setReturnUrl('http://localhost:8080/login/vk');
        $vk->scope = 'email';
        $url = $vk->buildAuthUrl();
        Yii::$app->getResponse()->redirect($url);
    }

    public function actionVk()
    {
        $code = Yii::$app->request->get('code');
        $vk = new VKontakte();
        $vk->clientId = '51433678';
        $vk->clientSecret = 'RMSSSU8DaKlSaLw7Gsj6';
        $vk->setReturnUrl('http://localhost:8080/login/vk');
        $accessToken = $vk->fetchAccessToken($code);
        $attributes = $vk->getUserAttributes();
        $attributes['email'] = ArrayHelper::getValue($accessToken->params, 'email');

        $auth = Auth::find()->where([
            'source' => $vk->getId(),
            'source_id' => $attributes['id'],
        ])->one();

        if ($auth) {
            Yii::$app->user->login($auth->user);
            return $this->goHome();
        }

        if (isset($attributes['email']) && User::find()->where(['email' => $attributes['email']])->exists()) {
            throw new UnexpectedValueException("Пользователь с такой электронной почтой уже существует, но не связан с Vkontakte. Для начала войдите на сайт использую электронную почту, для того, что бы связать её.");
        }




        var_dump($attributes);
    }
}