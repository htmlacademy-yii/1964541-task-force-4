<?php

namespace app\models\forms;

use app\models\Category;
use yii\base\Model;

class FilterForm extends Model
{
    public string|array $category = [];
    public bool $noExecutor = false;
    public string $period = '';

    const ONE_HOUR = '1 hour';
    const TWELVE_HOURS = '12 hours';
    const TWENTY_FOUR_HOURS = '24 hours';

    public function attributeLabels(): array
    {
        return [
            'category' => 'Категории',
            'noExecutor' => 'Без исполнителя',
            'period' => 'Период'
        ];
    }

    public function rules() {
        return [
            [['noExecutor'], 'boolean'],
            ['category', 'exist', 'targetClass' => Category::class, 'targetAttribute' => 'id'],
            ['period', 'in', 'range' => [self::ONE_HOUR, self::TWELVE_HOURS, self::TWENTY_FOUR_HOURS]]
        ];
    }

    public function periodAttributeLabels(): array
    {
        return [self::ONE_HOUR => '1 час', self::TWELVE_HOURS => '12 часов', self::TWENTY_FOUR_HOURS => '24 часа'];
    }

}