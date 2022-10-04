<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\forms\OptionsForm;
use app\models\forms\PasswordForm;
use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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
        if (Yii::$app->request->getIsPost()) {
            $optionsForm->load(Yii::$app->request->post());
            $optionsForm->file = UploadedFile::getInstance($optionsForm, 'file');

            if ($optionsForm->validate()) {

                if (!$optionsForm->loadToUser(Yii::$app->user->id)->save()) {
                    throw new ModelSaveException('Не удалось сохранить данные');
                }

                return $this->redirect('view/' . Yii::$app->user->id);
            }
        }
        return $this->render('options', ['model' => $optionsForm]);
    }

    public function actionSecurity()
    {
        $passwordForm = new PasswordForm();

        if (Yii::$app->request->getIsPost()) {
            $passwordForm->load(Yii::$app->request->post());

            if ($passwordForm->validate()) {

                if (!$passwordForm->loadToUser()->save()) {
                    throw new ModelSaveException('Не удалось сохранить модель');
                }

                return $this->redirect('view/' . Yii::$app->user->id);
            }
        }

        return $this->render('security', ['model' => $passwordForm]);
    }
}