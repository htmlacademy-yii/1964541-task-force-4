<?php

use yii\db\Migration;

/**
 * Class m220824_155529_insert_into_user
 */
class m220824_155529_insert_into_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'email' => '4208@gmail.com',
            'password' => 'passwordddd',
            'login' => 'Joji',
            'user_type' => 'customer',
            'rating' => 4,
            'city_id' => 1]);
        $this->insert('user', [
            'email' => '42088@gmail.com',
            'password' => 'passwore1dd',
            'login' => 'Lolik',
            'user_type' => 'customer',
            'rating' => 2,
            'city_id' => 2]);
        $this->insert('user', [
            'email' => '4208448@gmail.com',
            'password' => 'gewrgdddd',
            'login' => 'Bolik',
            'user_type' => 'customer',
            'rating' => 1,
            'city_id' => 3]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220824_155529_insert_into_user cannot be reverted.\n";

        return false;
    }
}
