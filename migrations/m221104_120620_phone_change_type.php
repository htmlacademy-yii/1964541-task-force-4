<?php

use yii\db\Migration;

/**
 * Class m221104_120620_phoneChangetype
 */
class m221104_120620_phone_change_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'phone', 'CHAR(64)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user', 'phone', 'INT');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221104_120620_phoneChangetype cannot be reverted.\n";

        return false;
    }
    */
}
