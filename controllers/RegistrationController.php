<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use app\models\User;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex() {
        $registrationForm = new RegistrationForm();
        return $this->render('registration', ['model' => $registrationForm]);
    }
}