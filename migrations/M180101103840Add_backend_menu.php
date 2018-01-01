<?php

namespace yuncms\note\migrations;

use yii\db\Migration;
use yii\db\Query;

class M180101103840Add_backend_menu extends Migration
{

    public function safeUp()
    {
        $this->insert('{{%admin_menu}}', [
            'name' => '笔记管理',
            'parent' => 8,
            'route' => '/note/note/index',
            'icon' => 'fa-file-archive-o',
            'sort' => NULL,
            'data' => NULL
        ]);

        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '笔记管理', 'parent' => 8])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['笔记查看', $id, '/note/note/view', 0, NULL],
            ['创建笔记', $id, '/note/note/create', 0, NULL],
            ['更新笔记', $id, '/note/note/update', 0, NULL],
        ]);
    }

    public function safeDown()
    {
        $id = (new Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '笔记管理', 'parent' => 8])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M180101103840Add_backend_menu cannot be reverted.\n";

        return false;
    }
    */
}
