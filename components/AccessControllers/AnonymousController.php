<?php

namespace app\components\AccessControllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class AnonymousController extends Controller
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