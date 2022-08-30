<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex() {

    }

    public function actionView($id) {
        $activeQuery = User::find();
        $activeQuery->joinWith('city');
        $activeQuery->andFilterWhere(['user.id' => $id]);
        $user = $activeQuery->one();
        if (!$user) {
            throw new NotFoundHttpException("Юзер с ID $id не найден");
        }
        return $this->render('view', ['user' => $user]);
    }
}