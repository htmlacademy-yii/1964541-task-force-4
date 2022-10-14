<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class StarsWidget extends Widget
{
    public $grade;
    const MAX_COUNT_FILL_STARS = 5;

    public function run()
    {
        $result = '';
        for ($i = 0; $i < self::MAX_COUNT_FILL_STARS; $i++) {
            $result .= Html::tag('span', '&nbsp;', [
                'class' => $this->grade > $i ? 'fill-star' : '',
            ]);
        }
        return $result;
    }
}