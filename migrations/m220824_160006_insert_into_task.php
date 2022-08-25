<?php

use yii\db\Migration;

/**
 * Class m220824_160006_insert_into_task
 */
class m220824_160006_insert_into_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('task', [
            'title' => 'Task',
            'description' => 'Надо перевести текст с китайского на японский',
            'city_id' => 1,
            'price' => 2400,
            'customer_id' => 1,
            'category_id' => 1,
            'status' => 'new']);
        $this->insert('task', [
            'title' => 'Новое задание',
            'description' => 'Построить дом плез',
            'city_id' => 2,
            'price' => 5000,
            'customer_id' => 2,
            'category_id' => 2,
            'status' => 'new']);
        $this->insert('task', [
            'title' => 'Task',
            'description' => 'Научите прогать',
            'city_id' => 3,
            'price' => 10100101,
            'customer_id' => 3,
            'category_id' => 3,
            'status' => 'new']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('task', ['id' => '1']);
        $this->delete('task', ['id' => '2']);
        $this->delete('task', ['id' => '3']);
        $this->execute("ALTER TABLE task AUTO_INCREMENT = 1");
    }
}
