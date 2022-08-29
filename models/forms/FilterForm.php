<?php

namespace app\models\forms;

use yii\base\Model;

class FilterForm extends Model
{
    public string|array $category = [];
    public bool $noExecutor = false;
    public string $period = '';

    const ONE_HOUR = '1 час';
    const TWELVE_HOURS = '12 часов';
    const TWENTY_FOUR_HOURS = '24 часа';

    public function rules() {
        return [
            [['noExecutor'], 'boolean'],
            [['category'], 'exist'],
            ['period', 'in', 'range' => [1, 2, 3]]
        ];
    }

    public function periodAttributeLabels(): array
    {
        return [1 => self::ONE_HOUR, 2 => self::TWELVE_HOURS, 3 => self::TWENTY_FOUR_HOURS];
    }

}