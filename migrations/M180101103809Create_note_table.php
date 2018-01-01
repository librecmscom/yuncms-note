<?php

namespace yuncms\note\migrations;

use yii\db\Migration;

class M180101103809Create_note_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%note}}', [
            'id' => $this->bigPrimaryKey(18)->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->comment('User Id'),
            'folder_id' => $this->integer()->unsigned()->comment('Folder Id'),
            'uuid' => $this->string(32)->comment('UUID'),
            'type' => $this->smallInteger(1)->defaultValue(0)->comment('Type'),
            'title' => $this->string()->defaultValue('Untitled')->comment('Title'),
            'format' => $this->string()->comment('Format'),
            'content' => $this->text()->notNull()->comment('Content'),
            'ip' => $this->string()->notNull()->comment('Ip'),
            'size' => $this->integer()->unsigned()->defaultValue(0)->comment('Size'),
            'views' => $this->integer()->unsigned()->defaultValue(0)->comment('Views'),
            'collections' => $this->integer()->unsigned()->defaultValue(0)->comment('Collections'),
            'expired_at' => $this->integer()->comment('Expired At'),
            'created_at' => $this->integer()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->createIndex('note_key', '{{%note}}', 'uuid', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%note}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180101103809Create_note_table cannot be reverted.\n";

        return false;
    }
    */
}
