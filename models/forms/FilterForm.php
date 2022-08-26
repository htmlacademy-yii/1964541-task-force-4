<?php

namespace app\models\forms;

use yii\base\Model;

class FilterForm extends Model
{
    public array $category = [];
    public bool $noExecutor = false;
    public string $period;

    public function rules() {
        return [
            [['category', 'noExecutor', 'period'], 'safe'],
            [['noExecutor'], 'boolean'],
            [['category'], 'exist'],
            ['category', 'each', 'rule' => ['courier', 'clean', 'cargo', 'neo', 'flat', 'repair', 'beauty', 'photo']],
            ['period', 'in', 'range' => ['1 час', '12 часов', '24 часа']]
        ];
    }

    public function categoryAttributeLabels()
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

    public function periodAttributeLabels() {
        return ['1 час' => '1 час', '12 часов' => '12 часов', '24 часа' => '24 часа'];
    }

}