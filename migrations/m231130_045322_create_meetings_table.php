<?php

use yii\db\Migration;

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
                ->string(60)
                ->notNull(),
            'dt_creation' => $this
                ->dateTime()
                ->defaultValue(new \yii\db\Expression('NOW()')),
            'dt_start' => $this
                ->dateTime()
                ->notNull(),
            'dt_end' => $this
                ->dateTime()
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
