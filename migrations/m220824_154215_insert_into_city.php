<?php

use yii\db\Migration;

/**
 * Class m220824_154215_insert_into_city
 */
class m220824_154215_insert_into_city extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('city', ['name' => 'London', 'lng' => 0.118092, 'lat' => 51.509865]);
        $this->insert('city', ['name' => 'Paris', 'lng' => 2.349014, 'lat' => 48.864716]);
        $this->insert('city', ['name' => 'Porto', 'lng' => 8.6110, 'lat' => 41.1496]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('city', ['name' => 'London']);
        $this->delete('city', ['name' => 'Paris']);
        $this->delete('city', ['name' => 'Porto']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220824_154215_insert_into_city cannot be reverted.\n";

        return false;
    }
    */
}
