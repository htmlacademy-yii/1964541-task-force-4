<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class AvatarWidget extends Widget
{
    public $avatar;
    public $width;
    public $height;
    public $class;

    public function init()
    {
        if ($this->avatar === null) {
            $this->avatar = '/img/man-glasses.png';
        }
    }

    public function run()
    {
        return Html::img($this->avatar, ['class' => $this->class, 'width' => $this->width, 'height' => $this->height]);
    }
}