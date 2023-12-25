<?php

use yii\db\Migration;

/**
 * Class m231225_080407_create_table_interview
 */
class m231225_080407_create_table_interview extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("interview",[
            'id'=>$this->primaryKey(),
            'status'=>$this->string()->notNull(),
            'created_date'=>$this->date()->notNull(),
            'time_at'=>$this->timestamp()->notNull(),
            'note_message'=>$this->string()->notNull(),
            'user_id'=>$this->integer()
        ]);
        $this->addForeignKey("user_id_fk",'interview','user_id','user','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231225_080407_create_table_interview cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231225_080407_create_table_interview cannot be reverted.\n";

        return false;
    }
    */
}
