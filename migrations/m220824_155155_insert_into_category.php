<?php

use yii\db\Migration;

/**
 * Class m220824_155155_insert_into_category
 */
class m220824_155155_insert_into_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('category', ['name' => 'Перевод', 'type' => 'Translation']);
        $this->insert('category', ['name' => 'Ремонт', 'type' => 'Repair']);
        $this->insert('category', ['name' => 'Образование', 'type' => 'Education']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('category', ['type' => 'Translation']);
        $this->delete('category', ['type' => 'Repair']);
        $this->delete('category', ['type' => 'Education']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220824_155155_insert_into_category cannot be reverted.\n";

        return false;
    }
    */
}
