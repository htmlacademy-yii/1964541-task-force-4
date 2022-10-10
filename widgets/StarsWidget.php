<?php

namespace app\widgets;

use yii\base\Widget;

class StarsWidget extends Widget
{
    public $grade;

    public function run()
    {
        return str_repeat("<span class='fill-star'>&nbsp;</span>", $this->grade) . (str_repeat("<span>&nbsp;</span>", (5 - $this->grade)));
    }
}