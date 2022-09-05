<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex() {
        $registrationForm = new RegistrationForm();
        if (Yii::$app->request->getIsPost()) {
            $registrationForm->load(Yii::$app->request->post());
            if ($registrationForm->validate()) {
                $registrationForm->loadToUser();
            }
        }
        return $this->render('registration', ['model' => $registrationForm]);
    }
}