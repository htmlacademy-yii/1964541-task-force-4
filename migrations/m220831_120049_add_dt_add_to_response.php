<?php

use yii\db\Migration;

/**
 * Class m220831_120049_add_dt_add_to_response
 */
class m220831_120049_add_dt_add_to_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('response', 'dt_add', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220831_120049_add_dt_add_to_response cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220831_120049_add_dt_add_to_response cannot be reverted.\n";

        return false;
    }
    */
}
