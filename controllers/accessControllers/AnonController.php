<?php

namespace app\controllers\AccessControllers;

use yii\filters\AccessControl;
use yii\web\Controller;

class AnonController extends Controller
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