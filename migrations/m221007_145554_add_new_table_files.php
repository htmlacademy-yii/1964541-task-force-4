<?php

use yii\db\Migration;

/**
 * Class m221007_145554_add_new_table_files
 */
class m221007_145554_add_new_table_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('files', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'file' => $this->string()->notNull()]);

        $this->addForeignKey('fk-files-task_id-task-id', 'files', 'task_id', 'task', 'id');

        $this->dropColumn('task', 'file');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-files-task_id-task-id', 'files');
        $this->dropTable('files');
        $this->addColumn('task', 'file', 'string');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221007_145554_add_new_table_files cannot be reverted.\n";

        return false;
    }
    */
}
