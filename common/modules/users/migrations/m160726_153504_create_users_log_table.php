<?php

namespace modules\users\migrations;

use yii\db\Migration;

/**
 * Handles the creation for table `users_log`.
 */
class m160726_153504_create_users_log_table extends Migration
{
    /**
     * @var string
     */
    public $table = 'users_log';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->notNull(),
            'type'       => $this->string(50)->notNull(),
            'message'    => $this->string(500)->notNull(),
            'params'     => $this->string(500),
            'data_old'   => 'mediumtext', // Todo: затестить
            'data_new'   => 'mediumtext', // Todo: затестить
            'url'        => $this->string(250),
            'ua'         => $this->string(250),
            'ip'         => $this->string(25),
            'created_at' => $this->integer(),
        ]);
        $this->createIndex('user_type', $this->table, ['user_id', 'type']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
