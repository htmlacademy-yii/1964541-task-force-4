<?php

namespace app\controllers;

use app\controllers\AccessControllers\AnonController;
use app\models\forms\RegistrationForm;
use TaskForce\exceptions\ModelSaveException;
use Yii;

class RegistrationController extends AnonController
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