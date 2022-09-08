<?php

namespace app\controllers\AccessControllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class AnonymousController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }
}