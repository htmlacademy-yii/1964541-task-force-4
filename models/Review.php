<?php

namespace app\models;

use app\models\forms\ResponseForm;
use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property string|null $dt_add
 * @property string $content
 * @property int $customer_id
 * @property int $executor_id
 * @property int|null $grade
 * @property int $task_id
 *
 * @property Task $task
 * @property User $user
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dt_add'], 'safe'],
            [['content', 'customer_id', 'task_id', 'executor_id'], 'required'],
            [['content'], 'string'],
            [['customer_id', 'grade', 'task_id'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'content' => 'Content',
            'customer_id' => 'Customer id',
            'executor_id' => 'Executor ID',
            'grade' => 'Grade',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    /**
     * Грузит данные из формы в модель класса Review
     * @param ResponseForm $responseForm экземпляр класса ResponseForm
     * @return void
     */
    public function loadForm($responseForm)
    {
        $this->content = $responseForm->content;
        $this->grade = $responseForm->grade;
        $this->executor_id = $responseForm->executorId;
        $this->task_id = $responseForm->taskId;
        $this->customer_id = $responseForm->customerId;
    }
}
