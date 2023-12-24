<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Handles the creation of table `employees`.
 */
class m231130_045811_create_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('employees', [
            'id' => $this
                ->primaryKey()
                ->unsigned()
                ->notNull(),
            'firstname' => $this
                ->string(255),
            'lastname' => $this
                ->string(255),
            'patronymic' => $this
                ->string(255),
            'login' => $this
                ->string(255)
                ->unique()
                ->notNull(),
            'date_creation' => $this
                ->timestamp()
                ->defaultValue(new Expression('NOW()')),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('employees');
    }
}
