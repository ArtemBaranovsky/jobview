<?php

namespace modules\users\migrations;

use yii\db\Migration;

class m170107_211026_create_users_utm_column extends Migration
{
    /**
     * @var string
     */
    public $table = '{{%users}}';

    public function up()
    {
        $this->addColumn($this->table, 'utm_source', $this->string(50)->null());
        $this->addColumn($this->table, 'utm_medium', $this->string(50)->null());
        $this->addColumn($this->table, 'utm_campaign', $this->string(50)->null());
        $this->addColumn($this->table, 'utm_term', $this->string(50)->null());
        $this->addColumn($this->table, 'utm_content', $this->string(50)->null());
    }

    public function down()
    {
        $this->dropColumn($this->table, 'utm_source');
        $this->dropColumn($this->table, 'utm_medium');
        $this->dropColumn($this->table, 'utm_campaign');
        $this->dropColumn($this->table, 'utm_term');
        $this->dropColumn($this->table, 'utm_content');
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
