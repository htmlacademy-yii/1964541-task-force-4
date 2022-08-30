<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Task;
use Yii;
use yii\base\Model;
use yii\db\Expression;

class FilterForm extends Model
{
    public string|array $category = [];
    public bool $noExecutor = false;
    public string $period = '';

    const ONE_HOUR = '1 hour';
    const TWELVE_HOURS = '12 hours';
    const TWENTY_FOUR_HOURS = '24 hours';

    public function getFilteredTasks(): array
    {
        $activeQuery = Task::find();
        $activeQuery->joinWith('city');
        $activeQuery->joinWith('category');
        $activeQuery->where(['status' => Task::STATUS_NEW]);
        if (Yii::$app->request->getIsPost()) {
            $this->load(Yii::$app->request->post());
            if (!$this->validate()) {
                $errors = $this->getErrors();
                return $activeQuery->all();
            }
            if (isset($this->category)) {
                $activeQuery->andFilterWhere(['category.id' => $this->category]);
            }
            if ($this->noExecutor) {
                $activeQuery->andWhere(['executor_id' => null]);
            }
            if ($this->period) {
                $this->chooseRightPeriod($activeQuery);
            }
        }
        $activeQuery->orderBy(['dt_add' => SORT_ASC]);

        return $activeQuery->all();
    }

    private function chooseRightPeriod($activeQuery): void
    {
        switch ($this->period) {
            case self::ONE_HOUR:
                $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 1 HOUR')]);
                return;
            case self::TWELVE_HOURS:
                $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 12 HOUR')]);
                return;
            case self::TWENTY_FOUR_HOURS:
                $activeQuery->andFilterWhere(['between', 'deadline', new Expression('NOW()'), new Expression('NOW() + INTERVAL 24 HOUR')]);
                return;
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
    }

}