<?php

use yii\db\Migration;

/**
 * Class m221003_075829_add_bdate_to_user
 */
class m221003_075829_add_bdate_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'bdate', 'TIMESTAMP');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'bdate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221003_075829_add_bdate_to_user cannot be reverted.\n";

        return false;
    }
    */
}
