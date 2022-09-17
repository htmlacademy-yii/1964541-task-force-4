<?php

use yii\db\Migration;

/**
 * Class m220917_114527_add_adress_column_to_task
 */
class m220917_114527_add_adress_column_to_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'address', 'CHAR(64)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task' ,'address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220917_114527_add_adress_column_to_task cannot be reverted.\n";

        return false;
    }
    */
}
