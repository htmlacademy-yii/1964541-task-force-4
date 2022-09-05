<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use yii\web\Controller;

class LandingController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();
        if ($loginForm->validate()) {
            echo 'OK';
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

}