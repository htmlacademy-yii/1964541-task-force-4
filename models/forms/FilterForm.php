<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;

class FilterForm extends Model
{
    public $category = [];
    public $noResponse = false;
    public $noAddress = false;
    public $period = '';

    const ONE_HOUR = '1 hour';
    const TWENTY_FOUR_HOURS = '24 hours';
    const ONE_WEEK = '1 week';

    /**
     * Возвращает массив правил валидации
     * @return array
     */
    public function rules() {
        return [
            [['noResponse'], 'boolean'],
            [['noAddress'], 'boolean'],
            [['category'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category' => 'id']]],
            ['period', 'in', 'range' => [self::ONE_HOUR, self::TWENTY_FOUR_HOURS, self::ONE_WEEK]]
        ];
    }

    /**
     * Возвращает массив лейблов для аттрибутов
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'category' => 'Категории',
            'noResponse' => 'Без откликов',
            'noAddress' => 'Удаленная работа',
            'period' => 'Период'
        ];
    }

    /**
     * Возвращает запрос на задания подходящие пользователю в сессии
     * @return ActiveQuery
     */
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

    /**
     * Добавляет фильтры в запрос в зависимости от данных формы
     * @return ActiveQuery
     */
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
        $activeQuery->orderBy(['dt_add' => SORT_DESC]);

        return $activeQuery;
    }

    /**
     * Меняет период в зависимости от данных формы
     * @param $activeQuery
     * @return ActiveQuery
     */
    private function chooseRightPeriod($activeQuery): ActiveQuery
    {
        switch ($this->period) {
            case self::ONE_HOUR:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 1 HOUR')]);
            case self::TWENTY_FOUR_HOURS:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 12 HOUR')]);
            case self::ONE_WEEK:
                return $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 1 WEEK')]);
        }
    }

    /**
     * Аттрибуты для лейблов периода
     * @return string[]
     */
    public function periodAttributeLabels(): array
    {
        return [self::ONE_HOUR => '1 час', self::TWENTY_FOUR_HOURS => '12 часов', self::ONE_WEEK => '1 неделя'];
    }

}