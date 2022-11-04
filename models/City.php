<?php

namespace app\models;

use TaskForce\exceptions\SourceFileException;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $lng
 * @property float|null $lat
 *
 * @property Task[] $tasks
 * @property User[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lng', 'lat'], 'number'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lng' => 'Lng',
            'lat' => 'Lat',
        ];
    }

    public static function getIdByName($cityName)
    {
        $city = City::findOne(['name' => $cityName]);
        if (!$city) {
            throw new SourceFileException('Такого города нет в таблице');
        }

        return $city->id;
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['city_id' => 'id']);
    }

    public static function getCityList()
    {
        $cityList = City::find()
            ->select('id, name')
            ->asArray()
            ->all();
        return ArrayHelper::map($cityList, 'id', 'name');
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['city_id' => 'id']);
    }
}
