<?php

use yii\db\Migration;

/**
 * Class m231226_065236_create_table_interview
 */
class m231226_065236_create_table_interview extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("interview", [
            'id' => $this->primaryKey(),
            'status' => $this->string()->notNull()->defaultValue("Yangi"),
            'created_date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'interview_date' => $this->date(),
            'interview_time' => $this->time(),
            'note_message' => $this->string(),
            'user_id' => $this->integer(),
            'job_id' => $this->integer(),
        ]);

        $this->addForeignKey("user_id_fk", 'interview', 'user_id', 'candidate', 'id');
        $this->addForeignKey('job_id_fk', 'interview', 'job_id', 'vacancy', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231226_065236_create_table_interview cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231226_065236_create_table_interview cannot be reverted.\n";

        return false;
    }
    */
}
