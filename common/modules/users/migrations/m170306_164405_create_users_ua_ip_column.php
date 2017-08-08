<?php

namespace modules\users\migrations;

use yii\db\Migration;

class m170306_164405_create_users_ua_ip_column extends Migration
{
    /**
     * @var string
     */
    public $table = '{{%users}}';

    public function up()
    {
        $this->addColumn($this->table, 'ip', $this->string(50)->null());
        $this->addColumn($this->table, 'ua', $this->string(100)->null());
    }

    public function down()
    {
        $this->dropColumn($this->table, 'ip');
        $this->dropColumn($this->table, 'ua');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
