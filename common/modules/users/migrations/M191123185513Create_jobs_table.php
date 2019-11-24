<?php

namespace modules\users\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `jobs`.
 * Has foreign keys to the tables:
 *
 * - `employment`
 */
class M191123185513Create_jobs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('jobs', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'salary' => $this->string()->notNull(),
            'employment_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `employment_id`
        $this->createIndex(
            'idx-jobs-employment_id',
            'jobs',
            'employment_id'
        );

        // add foreign key for table `employment`
        $this->addForeignKey(
            'fk-jobs-employment_id',
            'jobs',
            'employment_id',
            'employment',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `employment`
        $this->dropForeignKey(
            'fk-jobs-employment_id',
            'jobs'
        );

        // drops index for column `employment_id`
        $this->dropIndex(
            'idx-jobs-employment_id',
            'jobs'
        );

        $this->dropTable('jobs');
    }
}
