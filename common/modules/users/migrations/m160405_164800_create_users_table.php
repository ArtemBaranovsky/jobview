<?php

namespace modules\users\migrations;

use yii\db\Migration;

/**
 * Handles the creation for table `users`.
 */
class m160405_164800_create_users_table extends Migration
{
    /**
     * @var string
     */
    protected $table = '{{%users}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->table, [
            'id'                      => $this->primaryKey(),
            'auth_key'                => $this->string(255),
            'email'                   => $this->string(255)->notNull(),
            'password'                => $this->string(255)->notNull(),
            'name'                    => $this->string(50),
            'phone'                   => $this->string(50),
            'role'                    => $this->string(25),
            'error_count_auth'        => $this->integer(2),
            'time_registration'       => $this->integer(), // дата первой регистрации
            'time_last_visit'         => $this->integer(),
            'activate_code'           => $this->string(255),
            'activate_time'           => $this->integer()->defaultValue(0),
            'recovery_code'           => $this->string(255),
            'recovery_time'           => $this->integer()->defaultValue(0), // до какого момента активен код активации вроде как
            'subscribe_news'          => $this->string(50)->null(), // хранится код для отписки
            'subscribe_notifications' => $this->string(50)->null(), // это системные уведомления
            'ban_exists'              => $this->integer(1)->defaultValue(0),
            'ban_description'         => $this->string(1000)->null(),
        ]);

        $this->createIndex('auth', $this->table, ['auth_key']);
        $this->createIndex('email', $this->table, ['email'], true);
        $this->createIndex('recovery', $this->table, ['recovery_code']);
        $this->createIndex('activate', $this->table, ['activate_code']);
        $this->createIndex('subscribe_news', $this->table, ['subscribe_news']);
        $this->createIndex('subscribe_notifications', $this->table, ['subscribe_notifications']);
        $this->createIndex('ban', $this->table, ['ban_exists']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
        return true;
    }
}