<?php

use yii\db\Migration;

/**
 * Class m220831_111236_add_user_column_to_respone_and_review
 */
class m220831_111236_add_user_column_to_respone_and_review extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('response', 'customer_id', 'INT');
        $this->addColumn('review', 'executor_id', 'INT');
        $this->renameColumn('review', 'user_id', 'customer_id');
        $this->addForeignKey('response_ibfk_3', 'response', 'customer_id', 'user', 'id');
        $this->addForeignKey('review_ibfk_3', 'review', 'executor_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220831_111236_add_user_column_to_respone_and_review cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220831_111236_add_user_column_to_respone_and_review cannot be reverted.\n";

        return false;
    }
    */
}
