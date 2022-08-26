<?php

namespace app\models\forms;

use yii\base\Model;

class FilterForm extends Model
{
    public array $category;
    public bool $noExecutor;
    public string $period;

    public function rules() {
        return [
            [['noExecutor'], 'boolean'],
            [['category'], 'exist'],
            ['category', 'in', 'range' => ['courier', 'clean', 'cargo', 'neo', 'flat', 'repair', 'beauty', 'photo']],
            ['period', 'in', 'range' => ['1 час', '12 часов', '24 часа']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'courier' => 'Курьерские услуги',
            'clean' => 'Уборка',
            'cargo' => 'Переезды',
            'neo' => 'Компьютерная помощь',
            'flat' => 'Ремонт квартирный',
            'repair' => 'Ремонт техники',
            'beauty' => 'Красота',
            'photo' => 'Фото'
        ];
    }

}