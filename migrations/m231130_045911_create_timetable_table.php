<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles the creation of table `timetable`.
 */
class m231130_045911_create_timetable_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('timetable', [
            'id' => $this
                ->primaryKey()
                ->unsigned()
                ->notNull(),
            'meeting_id' => $this
                ->integer(11)
                ->unsigned()
                ->notNull(),
            'employee_id' => $this
                ->integer(11)
                ->unsigned()
                ->notNull(),
            'date_creation' => $this
                ->timestamp()
                ->defaultValue(new Expression('NOW()')),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-timetable-meeting_id',
            'timetable',
            'meeting_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-timetable-meeting_id',
            'timetable',
            'meeting_id',
            'meetings',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'idx-timetable-employee_id',
            'timetable',
            'employee_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-timetable-employee_id',
            'timetable',
            'employee_id',
            'employees',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-timetable-meeting_id', 'timetable');
        $this->dropIndex('idx-timetable-meeting_id', 'timetable');
        $this->dropForeignKey('fk-timetable-employee_id', 'timetable');
        $this->dropIndex('idx-timetable-employee_id', 'timetable');
        $this->dropTable('timetable');
    }
}
