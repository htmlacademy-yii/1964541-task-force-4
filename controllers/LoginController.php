<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();

        if (Yii::$app->request->isAjax && $loginForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            ActiveForm::validate($loginForm);

            if ($loginForm->validate()) {
                echo 'OK';
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);
                $this->goHome();
            }
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

}