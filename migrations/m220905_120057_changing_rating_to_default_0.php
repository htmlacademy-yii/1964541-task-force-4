<?php

use yii\db\Migration;

/**
 * Class m220905_120057_changing_rating_to_default_0
 */
class m220905_120057_changing_rating_to_default_0 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user','rating', 'INT DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user','rating', 'INT NOT NULL');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220905_120057_changing_rating_to_default_0 cannot be reverted.\n";

        return false;
    }
    */
}
