<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

class FilterForm extends Model
{
    public $category = [];
    public $noResponse = false;
    public $noAddress = false;
    public $period = '';

    const ONE_HOUR = '1 hour';
    const TWELVE_HOURS = '12 hours';
    const TWENTY_FOUR_HOURS = '24 hours';

    public function getTasksQuery(): ActiveQuery
    {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->leftJoin('response', 'task.id = response.task_id');
        $activeQuery->where(['task.status' => Task::STATUS_NEW]);
        $activeQuery->andWhere(['or', ['city_id' => Yii::$app->user->identity->city_id], ['city_id' => null]]);
        return $activeQuery;
    }

    public function getFilteredTasksData(): ActiveQuery
    {
        $activeQuery = $this->getTasksQuery();

        if (isset($this->category)) {
            $activeQuery->andFilterWhere(['category.id' => $this->category]);
        }
        if ($this->noResponse) {
            $activeQuery->andFilterWhere(['is', 'response.id', new Expression('null')]);
        }
        if ($this->noAddress) {
            $activeQuery->andFilterWhere(['is', 'address', new Expression('null')]);
        }
        if ($this->period) {
            $this->chooseRightPeriod($activeQuery);
        }
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);

        return $activeQuery;
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
            'noResponse' => 'Без откликов',
            'noAddress' => 'Удаленная работа',
            'period' => 'Период'
        ];
    }

    public function rules() {
        return [
            [['noResponse'], 'boolean'],
            [['noAddress'], 'boolean'],
            [['category'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']]],
            ['period', 'in', 'range' => [self::ONE_HOUR, self::TWELVE_HOURS, self::TWENTY_FOUR_HOURS]]
        ];
    }

    public function periodAttributeLabels(): array
    {
        return [self::ONE_HOUR => '1 час', self::TWELVE_HOURS => '12 часов', self::TWENTY_FOUR_HOURS => '24 часа'];
    }

}