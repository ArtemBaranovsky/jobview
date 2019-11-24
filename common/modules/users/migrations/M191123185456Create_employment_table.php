<?php

namespace modules\users\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `employment`.
 */
class M191123185456Create_employment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employment', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);

        $this->batchInsert('{{%employment}}', ['title'], [
                ['полная занятость'],
                ['частичная занятость'],
                ['удаленная работа'],
                ['готовы взять студента'],
                ['готовы взять человека с инвалидностью'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('employment');
    }
}
