<?php

use yii\db\Migration;

/**
 * Class m220912_110625_respinses_status_add
 */
class m220912_110625_responses_status_add extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('response', 'status', "ENUM ('new', 'accepted', 'canceled') DEFAULT 'new'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('response', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220912_110625_respinses_status_add cannot be reverted.\n";

        return false;
    }
    */
}
