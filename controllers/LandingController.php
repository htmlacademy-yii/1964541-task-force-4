<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\web\Controller;

class LandingController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();
        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());
            if ($loginForm->validate()) {
                echo 'OK';
            }
        }

        return $this->render('landing', ['model' => $loginForm]);
    }

}