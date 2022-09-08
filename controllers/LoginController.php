<?php

namespace app\controllers;

use app\controllers\AccessControllers\AnonymousController;
use app\models\forms\LoginForm;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends AnonymousController
{

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
                return $this->goHome();
            }
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

}