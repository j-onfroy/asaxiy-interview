<?php

use yii\db\Migration;

/**
 * Class m231225_132243_create_table_vacancy
 */
class m231225_132243_create_table_vacancy extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("vacancy", [
            'id' => $this->primaryKey(),
            'job_title' => $this->string()->notNull(),
            'skills' => $this->string(),
            'job_about' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231225_132243_create_table_vacancy cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231225_132243_create_table_vacancy cannot be reverted.\n";

        return false;
    }
    */
}
