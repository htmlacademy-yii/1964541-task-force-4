<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();
        if (Yii::$app->request->isAjax && $loginForm->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($loginForm);
            }
            if ($loginForm->validate()) {
                echo 'OK';
            }

        return $this->render('landing', ['model' => $loginForm]);
    }

}