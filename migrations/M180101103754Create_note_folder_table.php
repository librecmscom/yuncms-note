<?php

namespace yuncms\note\migrations;

use yii\db\Migration;

class M180101103754Create_note_folder_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%note_folder}}', [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->comment('User Id'),
            'name' => $this->string()->defaultValue('Untitled')->comment('name'),
            'created_at' => $this->integer()->notNull()->unsigned()->comment('Created At'),
            'updated_at' => $this->integer()->notNull()->unsigned()->comment('Updated At'),
        ], $tableOptions);

        $this->addForeignKey('{{%note_folder_fk_1}}', '{{%note_folder}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }


    public function safeDown()
    {
        $this->dropTable('{{%note_folder}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180101103754Create_note_folder_table cannot be reverted.\n";

        return false;
    }
    */
}
