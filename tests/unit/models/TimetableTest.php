<?php

namespace tests\unit\models;

use yii\db\Exception;

use Codeception\Test\Unit;
use Faker\Factory;

use app\models\Timetable;

class TimetableTest extends Unit
{
    /**
     * Проверка на валидность полей модели Timetable.
     *
     * @return void
     */
    public function testValidation(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //act
        $model->setAttribute('meeting_id', $faker->numberBetween(1, 5));
        //assert
        $this->assertTrue($model->validate(['meeting_id']));

        //act
        $model->setAttribute('meeting_id', null);
        //assert
        $this->assertFalse($model->validate(['meeting_id']));

        //act
        $model->setAttribute('meeting_id', $faker->text(10));
        //assert
        $this->assertFalse($model->validate(['meeting_id']));

        //act
        $model->setAttribute('employee_id', $faker->numberBetween(1, 5));
        //assert
        $this->assertTrue($model->validate(['employee_id']));

        //act
        $model->setAttribute('employee_id', null);
        //assert
        $this->assertFalse($model->validate(['employee_id']));

        //act
        $model->setAttribute('employee_id', $faker->text(10));
        //assert
        $this->assertFalse($model->validate(['employee_id']));
    }

    /**
     * Проверка: Метод поиска записей расписания вернет массив данных,
     * для указанного сотрудника
     *
     * @return void
     */
    public function testFindRecordsForEmployee(): void
    {
        //arrange
        $model = new Timetable();

        //act
        $timetableRecords = $model
            ->findRecordsForEmployee(1)
            ->all();

        //assert
        verify($timetableRecords)->notEmpty();
        verify($timetableRecords)->isArray();
    }

    /**
     * Проверка: Метод поиска записей расписания вернет пустой массив,
     * если передать несуществующий ID сотрудника
     * @return void
     */
    public function testFindRecordsForEmployeeWithNonExistentId(): void
    {
        //arrange
        $model = new Timetable();

        //act
        $query = $model
            ->findRecordsForEmployee(100000)
            ->all();

        //assert
        verify($query)->empty();
    }

    /**
     * Проверка: Метод поиска записей расписания вернет пустой массив,
     * если передать ID сотрудника неверного формата
     * @return void
     */
    public function testFindRecordsForEmployeeWithInvalidId(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //assert
        $query = $model
            ->findRecordsForEmployee('test')->all();

        //assert
        verify($query)->empty();
    }
}
