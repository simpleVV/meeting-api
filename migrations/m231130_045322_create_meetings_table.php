<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles the creation of table `meetings`.
 */
class m231130_045322_create_meetings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('meetings', [
            'id' => $this
                ->primaryKey()
                ->unsigned()
                ->notNull(),
            'title' => $this
                ->string(255)
                ->notNull(),
            'date_creation' => $this
                ->timestamp()
                ->defaultValue(new Expression('NOW()')),
            'meeting_date' => $this
                ->date()
                ->notNull(),
            'start_time' => $this
                ->time()
                ->notNull(),
            'end_time' => $this
                ->time()
                ->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('meetings');
    }
}
