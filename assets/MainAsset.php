<?php

namespace app\assets;

use yii\web\AssetBundle;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $css = [
        'css/style.css'
    ];

    public $js = [
        'js/main.js',
        'js/starsGrade.js',
        'js/autocomplete.js'
    ];

}
