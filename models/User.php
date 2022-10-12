<?php

namespace app\models;

use TaskForce\exceptions\SourceFileException;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $login
 * @property string|null $dt_add
 * @property string|null $avatar
 * @property string|null $user_type
 * @property int|null $city_id
 * @property int|null $phone
 * @property string|null $telegram
 * @property string|null $description
 * @property string $bdate
 *
 * @property Category[] $categories
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserCategory[] $userCategories
 * @property Auth $auth
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public const CUSTOMER_STATUS = 'customer';
    public const EXECUTOR_STATUS = 'executor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'login'], 'required'],
            [['dt_add'], 'safe'],
            [['avatar', 'user_type'], 'string'],
            [['city_id', 'phone'], 'integer'],
            [['email', 'login'], 'string', 'max' => 320],
            [['password', 'telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['login'], 'unique'],
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
            'email' => 'Email',
            'password' => 'Password',
            'login' => 'Login',
            'dt_add' => 'Dt Add',
            'avatar' => 'Avatar',
            'user_type' => 'User Type',
            'city_id' => 'City ID',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('user_category', ['user_id' => 'id']);
    }

    public function getRatingPosition()
    {
        $rowsArray = User::find()
            ->select("user.id,
       ROW_NUMBER() OVER (ORDER BY SUM(r.grade) / (COUNT(r.id) + (
           SELECT COUNT(t.id)
           FROM task t
           WHERE status = 'failed'
             AND user.id = t.executor_id)) DESC) AS `row_num`,
       SUM(r.grade) / (COUNT(r.id) + (
           SELECT COUNT(t.id)
           FROM task t
           WHERE status = 'failed'
             AND user.id = t.executor_id)) as rating")
            ->leftJoin('review r', 'user.id = r.executor_id')
            ->groupBy('user.id')
            ->asArray()
            ->all();

        return ArrayHelper::map($rowsArray, 'id', 'row_num')[$this->id];
    }

    public function getUserAge()
    {
        return $this->bdate ? ', ' . date('Y', time()) - date( 'Y', strtotime($this->bdate)) . ' лет' : '';
    }

    public function getUserRating()
    {
        $gradeSum = Review::find()->where(['executor_id' => $this->id])->sum('grade');
        $reviewCount = Review::find()->where(['executor_id' => $this->id])->count();
        $failedTasks = Task::find()->where(['executor_id' => $this->id])->andFilterWhere(['status' => Task::STATUS_FAILED])->count();

        if (($reviewCount + $failedTasks) === 0) {
            return 0;
        }

        return floor($gradeSum / ($reviewCount + $failedTasks));
    }

    public function getExecutedTasks()
    {
        return Task::find()
            ->andFilterWhere(['executor_id' => $this->id])
            ->andFilterWhere(['status' => Task::STATUS_EXECUTED]);
    }

    public function getFailedTasks()
    {
        return Task::find()
            ->andFilterWhere(['executor_id' => $this->id])
            ->andFilterWhere(['status' => Task::STATUS_FAILED]);
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
     * Проверяет наличие регистрации через сторонний сервис
     * @return bool
     */
    public function isSecurityAvailable(): bool
    {
        if (!Auth::findOne(['user_id' => $this->id])) {

            return true;
        }

        return false;
    }

    public function isBusy()
    {
        if (Task::findOne(['executor_id' => $this->id, 'status' => Task::STATUS_IN_WORK])){

            return true;
        }

        return false;
    }

    /**
     * Gets query for [[Auth]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuth()
    {
        return $this->hasOne(Auth::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorTasks()
    {
        return $this->hasMany(Task::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerTasks()
    {
        return $this->hasMany(Task::className(), ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['user_id' => 'id']);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Загружает инфу полученную от  VK в User
     * @param array $userInfo Массив Информации о пользователе
     * @return void
     * @throws SourceFileException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function loadAuthUser($userInfo)
    {
        $this->email = $userInfo['email'];
        $this->login = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
        $this->avatar = $userInfo['photo'];
        $this->password = Yii::$app->security->generateRandomString(8);
        $this->city_id = City::getIdByName($userInfo['city']['title']);
        $this->bdate = Yii::$app->formatter->asDate($userInfo['bdate'], 'php:Y-m-d');
    }

}
