<?php

use yii\db\Migration;

/**
 * Class m220926_152434_change_task_long_lat_float_to_double
 */
class m220926_152434_change_task_long_lat_float_to_decimal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'long', 'DECIMAL(11,8)');
        $this->alterColumn('task', 'lat', 'DECIMAL(11,8)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('task', 'long', 'FLOAT');
        $this->alterColumn('task', 'lat', 'FLOAT');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220926_152434_change_task_long_lat_float_to_double cannot be reverted.\n";

        return false;
    }
    */
}
