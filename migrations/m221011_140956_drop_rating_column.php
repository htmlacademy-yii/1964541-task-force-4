<?php

use yii\db\Migration;

/**
 * Class m221011_140956_drop_rating_column
 */
class m221011_140956_drop_rating_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user', 'rating');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('user', 'rating', 'int');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221011_140956_drop_rating_column cannot be reverted.\n";

        return false;
    }
    */
}
