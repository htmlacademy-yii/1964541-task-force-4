<?php

namespace app\models;

use Yii;

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
 * @property int $rating
 * @property int|null $city_id
 * @property int|null $phone
 * @property string|null $telegram
 *
 * @property Category[] $categories
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserCategory[] $userCategories
 */
class User extends \yii\db\ActiveRecord
{
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
            [['email', 'password', 'login', 'rating'], 'required'],
            [['dt_add'], 'safe'],
            [['avatar', 'user_type'], 'string'],
            [['rating', 'city_id', 'phone'], 'integer'],
            [['email', 'login'], 'string', 'max' => 320],
            [['password', 'telegram'], 'string', 'max' => 64],
            [['email'], 'unique'],
            [['login'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
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
            'rating' => 'Rating',
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
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('user_category', ['user_id' => 'id']);
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
        return $this->hasMany(Review::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
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
}
