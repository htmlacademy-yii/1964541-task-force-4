<?php

namespace app\controllers;

use app\components\AccessControllers\SecuredController;
use app\models\Auth;
use app\models\forms\OptionsForm;
use app\models\forms\PasswordForm;
use app\models\User;
use TaskForce\exceptions\ModelSaveException;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UserController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['security'],
            'matchCallback' => function ($rule, $action) {
                return Auth::findOne(['user_id' => Yii::$app->user->id]);
            }];
        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionView($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("Юзер с ID $id не найден");
        }

        if ($user->user_type === USER::CUSTOMER_STATUS) {
            throw new HttpException('404', 'Доступ запрещен');
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
                $optionsForm->loadToUser();

                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
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
                $passwordForm->loadToUser();

                return $this->redirect(['view', 'id' => Yii::$app->user->id]);
            }
        }

        return $this->render('security', ['model' => $passwordForm]);
    }
}