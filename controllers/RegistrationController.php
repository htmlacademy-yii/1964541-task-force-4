<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex() {
        $user = new User();
        return $this->render('registration', ['model' => $user]);
    }
}