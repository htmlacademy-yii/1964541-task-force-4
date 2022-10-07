<?php

namespace app\models;

use TaskForce\actions\ActionAccept;
use TaskForce\actions\ActionReject;
use TaskForce\actions\ActionExecute;
use TaskForce\actions\ActionCancel;
use TaskForce\exceptions\StatusNotExistsException;
use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property float|null $long
 * @property float|null $lat
 * @property int|null $city_id
 * @property int $price
 * @property int $customer_id
 * @property int|null $executor_id
 * @property string|null $status
 * @property int $category_id
 * @property string|null $deadline
 * @property string|null $dt_add
 * @property string|null $address
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Response[] $responses
 * @property Review[] $reviews
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_EXECUTED = 'executed';
    const STATUS_FAILED = 'failed';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CLOSED = 'closed';

    public static $statusMap = [
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_EXECUTED => 'Выполнено',
        self::STATUS_NEW => 'Новое',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_FAILED => 'Провалено'
    ];

    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'price', 'customer_id', 'category_id'], 'required'],
            [['description', 'status'], 'string'],
            [['long', 'lat'], 'number'],
            [['city_id', 'price', 'customer_id', 'executor_id', 'category_id'], 'integer'],
            [['deadline', 'dt_add'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [
                ['category_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']
            ],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['executor_id' => 'id']
            ],
            [
                ['customer_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['customer_id' => 'id']
            ],
            [
                ['city_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => City::className(),
                'targetAttribute' => ['city_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'long' => 'Long',
            'lat' => 'Lat',
            'city_id' => 'City ID',
            'price' => 'Price',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'status' => 'Status',
            'category_id' => 'Category ID',
            'deadline' => 'Deadline',
            'dt_add' => 'Dt Add',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
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
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['task_id' => 'id']);
    }

    public function checkUserResponse($id)
    {
        if (Response::find()->andFilterWhere(['task_id' => $this->id, 'executor_id' => $id, 'status' => Response::STATUS_NEW])->orFilterWhere(['task_id' => $this->id, 'executor_id' => $id, 'status' => Response::STATUS_CANCELED])->one()) {
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['task_id' => 'id']);
    }

    public function getStatusLabel(): string
    {
        return self::$statusMap[$this->status];
    }

    public function getAvailableActions(int $id): array
    {
        switch ($this->status) {
            case self::STATUS_NEW:
                return $id === $this->customer_id ? [new ActionReject($this->customer_id, $this->executor_id, $this->id)] : [new ActionAccept($this->customer_id, $this->executor_id, $this->id)];
            case self::STATUS_IN_WORK:
                if ($id === $this->executor_id) {
                    return [new ActionCancel($this->customer_id, $this->executor_id, $this->id)];
                }
                if ($id === $this->customer_id) {
                    return [new ActionExecute($this->customer_id, $this->executor_id, $this->id), new ActionReject($this->customer_id, $this->executor_id, $this->id)];
                }
                return [null];
            default:
                return [null];
        }
    }
}
