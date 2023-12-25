<?php

use yii\db\Migration;

/**
 * Class m231225_071231_create_table_user
 */
class m231225_071231_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user',[
            'id'=>$this->primaryKey(),
            'first_name'=>$this->string()->notNull(),
            'last_name'=>$this->string()->notNull(),
            'address'=>$this->string()->notNull()->check('CHAR_LENGTH([[address]]) > 10'),
            'country_of_origin'=>$this->string(),
            'email'=>$this->string()->notNull()->unique()->check("email REGEXP '^\\w+([.-]?\\w+)*@\\w+([.-]?\\w+)*(\\.\\w{2,})+$'"),
            'phone_number'=>$this->string()->notNull()->unique()->check("phone_number REGEXP '^\\+998\\d{9}$'"),
            'birthday'=>$this->timestamp()->notNull(),
            'resume_url'=>$this->string()->notNull(),
            'hired'=>$this->boolean()->defaultValue(false)

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231225_071231_create_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231225_071231_create_table_user cannot be reverted.\n";

        return false;
    }
    */
}
