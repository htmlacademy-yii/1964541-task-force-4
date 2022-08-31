<?php

use yii\db\Migration;

/**
 * Class m220831_101147_add_description_column_to_user
 */
class m220831_101147_add_description_column_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'description', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220831_101147_add_description_column_to_user cannot be reverted.\n";

        return false;
    }
    */
}
