<?php

use yii\db\Migration;

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
            'id' => $this->primaryKey()
                ->unsigned()
                ->notNull(),
            'firstname' => $this->string(60)
                ->notNull(),
            'lastname' => $this->string(60)
                ->notNull(),
            'patronymic' => $this->string(60),
            'login' => $this->string(60)
                ->unique()
                ->notNull(),
            'dt_creation' => $this->dateTime()
                ->defaultValue(new \yii\db\Expression('NOW()')),
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
