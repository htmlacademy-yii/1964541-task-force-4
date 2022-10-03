<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\forms\OptionsForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends SecuredController
{
    public function actionView($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("Юзер с ID $id не найден");
        }

        return $this->render('view', ['user' => $user]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionOptions()
    {
        $optionsForm = new OptionsForm();
        $optionsForm->load(Yii::$app->request->post());

        if ($optionsForm->validate()) {
            var_dump($optionsForm);
            var_dump($optionsForm->loadToUser(Yii::$app->user->id));
        }

        return $this->render('options', ['model' => $optionsForm]);
    }
}