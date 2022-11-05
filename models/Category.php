<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 *
 * @property Task[] $tasks
 * @property UserCategory[] $userCategories
 * @property User[] $users
 */
class Category extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * Возвращает массив правил валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'type'], 'string', 'max' => 128],
            [['type'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * Аттрибуты ле
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_category', ['category_id' => 'id']);
    }

    /**
     * Лист доступных категорий
     * @return array
     */
    public static function getCategoriesList(): array
    {
        $data = static::find()
            ->select(['id', 'name'])
            ->orderBy('id')
            ->asArray()
            ->all();
        return ArrayHelper::map($data, 'id', 'name');
    }
}
