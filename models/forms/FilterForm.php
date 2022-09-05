<?php

namespace app\models\forms;

<<<<<<< HEAD
use app\models\Category;
use app\models\Task;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;

class FilterForm extends Model
{
    public $category = [];
    public $noExecutor = false;
    public $period = '';

    const ONE_HOUR = '1 hour';
    const TWELVE_HOURS = '12 hours';
    const TWENTY_FOUR_HOURS = '24 hours';

    public function getTasksQuery(): ActiveQuery
    {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['status' => Task::STATUS_NEW]);
        return $activeQuery;
    }

    public function getFilteredTasks(): array
    {
        $activeQuery = $this->getTasksQuery();

        if (isset($this->category)) {
            $activeQuery->andFilterWhere(['category.id' => $this->category]);
        }
        if ($this->noExecutor) {
            $activeQuery->andWhere(['executor_id' => null]);
        }
        if ($this->period) {
            $this->chooseRightPeriod($activeQuery);
        }
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);

        return $activeQuery->all();
    }

    private function chooseRightPeriod($activeQuery): ActiveQuery
    {
        switch ($this->period) {
            case self::ONE_HOUR:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 1 HOUR')]);
            case self::TWELVE_HOURS:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 12 HOUR')]);
            case self::TWENTY_FOUR_HOURS:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 24 HOUR')]);
        }
    }


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
            [['category'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']]],
            ['period', 'in', 'range' => [self::ONE_HOUR, self::TWELVE_HOURS, self::TWENTY_FOUR_HOURS]]
        ];
    }

    public function periodAttributeLabels(): array
    {
        return [self::ONE_HOUR => '1 час', self::TWELVE_HOURS => '12 часов', self::TWENTY_FOUR_HOURS => '24 часа'];
=======
use yii\base\Model;

class FilterForm extends Model
{
    public array $category;
    public bool $noExecutor;
    public string $period;

    public function rules() {
        return [
            ['noExecutor' => 'boolean'],
            ['category' => 'exist'],
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
>>>>>>> 0b9615f
    }

}