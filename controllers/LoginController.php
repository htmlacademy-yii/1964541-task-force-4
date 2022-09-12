<?php

namespace app\controllers;

use app\components\AccessControllers\AnonymousController;
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
}