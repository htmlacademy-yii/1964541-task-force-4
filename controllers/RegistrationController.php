<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\web\Controller;

class RegistrationController extends Controller
{
    public function actionIndex()
    {
        $registrationForm = new RegistrationForm();
        if (Yii::$app->request->getIsPost()) {
            $registrationForm->load(Yii::$app->request->post());
            if ($registrationForm->validate()) {
                if (!$registrationForm->loadToUser()->save()) {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }
                Yii::$app->response->redirect(['task']);
            }
        }
        return $this->render('registration', ['model' => $registrationForm]);
    }
}