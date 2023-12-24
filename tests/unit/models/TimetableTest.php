<?php

namespace tests\unit\models;

use yii\db\Exception;
use yii\helpers\ArrayHelper;

use Codeception\Test\Unit;
use Codeception\Verify\Verify;
use Faker\Factory;

use app\models\Timetable;

class TimetableTest extends Unit
{
    /**
     * Проверка на валидность meeting_id модели Timetable.
     *
     * @return void
     */
    public function testValidateMeetingId()
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
    }

    /**
     * Проверка на валидность employee_id модели Timetable.
     *
     * @return void
     */
    public function testValidateEmployeeId()
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

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
     * Проверка: Метод поиска собраний на указанную дату вернет массив данных,
     * если передать ID сотрудника и актуальную дату
     *
     * @return void
     */
    public function testFindMeetingsForEmployeeWithValidParams(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //act
        $meetings = $model
            ->findMeetingsForEmployee(1, '2023-12-20')
            ->all();

        //assert
        verify($meetings)->notEmpty();
        verify($meetings)->isArray();
    }

    /**
     * Проверка: Метод поиска собраний на указанную дату вернет пустой
     * массив, если передать не актуальную дату
     *
     * @return void
     */
    public function testFindMeetingsForEmployeeWithNotActualDate(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //act
        $meetings = $model
            ->findMeetingsForEmployee(
                $faker->numberBetween(1, 5),
                $faker->date('Y-m-d', '2000-04-10')
            )
            ->all();

        //assert
        verify($meetings)->empty();
    }

    /**
     * Проверка: Метод поиска собраний на указанную дату вернет пустой
     * массив, если передать не актуальную дату
     *
     * @return void
     */
    public function testFindMeetingsForEmployeeWithNotActualEmployeeId(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //act
        $meetings = $model
            ->findMeetingsForEmployee(
                $faker->numberBetween(100, 150),
                $faker->date('Y-m-d', '2023-12-20')
            )
            ->all();

        //assert
        verify($meetings)->empty();
    }

    /**
     * Проверка: Метод поиска собраний на указанную дату выбросит
     * исключение, если передать не валидный параметр 
     *
     * @return void
     */
    public function testFindMeetingsForEmployeeWithInvalidDate(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //assert
        $this->expectException(Exception::class);
        $model->findMeetingsForEmployee(
            $faker->numberBetween(1, 5),
            $faker->randomElement(['a', 1])
        )->all();
    }

    /**
     * Проверка: Метод поиска подходящих собраний вернет массив
     * собраний, если передать ID сотрудника и актуальную дату
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithValidParams(): void
    {
        //arrange
        $model = new Timetable();
        //act
        $meetings = $model
            ->findAvailableMeetings(1, '2023-12-20');

        //assert
        verify($meetings)->notNull();
        verify($meetings)->isArray();
    }

    /**
     * Проверка: Метод поиска подходящих собраний вернет массив
     * собраний, которые не пересекаются с другими собраниями
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithoutIntersections(): void
    {
        //arrange
        $model = new Timetable();

        $allMeetings = $model
            ->findMeetingsForEmployee(1, '2023-12-20')
            ->asArray()
            ->all();

        $meetingTitles = ArrayHelper::getColumn($allMeetings, 'title');
        Verify::Array($meetingTitles)
            ->contains('Собрание 2');

        //act
        $meetings = $model
            ->findAvailableMeetings(1, '2023-12-20');

        $meetingTitles = ArrayHelper::getColumn($meetings, 'title');

        //assert
        verify($meetings)->notNull();
        verify($meetings)->isArray();

        Verify::Array($meetingTitles)
            ->notContains('Собрание 2');
    }

    /**
     * Проверка: Метод поиска подходящих собраний вернет пустой массив,
     * если передать дату, на которую нет собраний
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithNotActualDate(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //act
        $meetings = $model
            ->findAvailableMeetings(
                1,
                $faker->date('Y-m-d', '2000-04-10')
            );

        //assert
        verify($meetings)->isArray();
        verify($meetings)->empty();
    }

    /**
     * Проверка: Метод поиска подходящих собраний выбросит исключение,
     * если передать дату не верного формата
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithInvalidData(): void
    {
        //arrange
        $model = new Timetable();
        $faker = Factory::create();

        //assert
        $this->expectException(Exception::class);

        $meetings = $model
            ->findAvailableMeetings(
                1,
                $faker->text()
            );

        verify($meetings)->null();
    }
}
