<?php

namespace tests\unit\models;

use yii\helpers\ArrayHelper;
use yii\db\Exception;

use Codeception\Test\Unit;
use Faker\Factory;

use app\models\Meeting;
use app\models\Timetable;
use Codeception\Verify\Verify;

class MeetingTest extends Unit
{
    /**
     * Проверка на валидность полей модели Meeting
     *
     * @return void
     */
    public function testValidation(): void
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //act
        $model->setAttribute('title', null);
        //assert
        $this->assertFalse($model->validate(['title']));
        //act
        $model->setAttribute('title', $faker->title);
        //assert
        $this->assertTrue($model->validate(['title']));
        //act
        $model->setAttribute('title', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['title']));
        //act
        $model->setAttribute('title', $faker->realTextBetween(160, 200));
        //assert
        $this->assertFalse($model->validate(['title']));
        //act
        $model->setAttribute('dt_start', $faker->date('Y-m-d H:i:s', 'now'));
        //assert
        $this->assertTrue($model->validate(['dt_start']));
        //act
        $model->setAttribute('dt_start', $faker->date('Y-m-d', 'now'));
        //assert
        $this->assertFalse($model->validate(['dt_start']));
        //act
        $model->setAttribute('dt_start', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['dt_start']));
        //act
        $model->setAttribute('dt_start', null);
        //assert
        $this->assertFalse($model->validate(['dt_start']));
        //act
        $model->setAttribute('dt_end', $faker->date('Y-m-d H:i:s', 'now'));
        //assert
        $this->assertTrue($model->validate(['dt_end']));
        //act
        $model->setAttribute('dt_end', $faker->date('Y-m-d', 'now'));
        //assert
        $this->assertFalse($model->validate(['dt_end']));
        //act
        $model->setAttribute('dt_end', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['dt_end']));
        //act
        $model->setAttribute('dt_end', null);
        //assert
        $this->assertFalse($model->validate(['dt_end']));
    }

    /**
     * Проверка: Метод поиска собраний на указанную дату вернет
     * массив подходящих собраний, если передать актуальную дату
     *
     * @return void
     */
    public function testFindMeetingsForCurrentDateWithActualDate(): void
    {
        //arrange
        $model = new Meeting();

        //act
        $meetings = $model
            ->findMeetingsForCurrentDate('2023-12-04')
            ->all();

        //assert
        verify($meetings)->notNull();
        verify($meetings[0]->title)
            ->equals('Собрание 1');
    }

    /**
     * Проверка: Метод поиска собраний на указанную дату вернет пустой
     * массив, если передать не актуальную дату
     *
     * @return void
     */
    public function testFindMeetingsForCurrentDateWithNotActualDate(): void
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //act
        $meetings = $model
            ->findMeetingsForCurrentDate($faker->date('Y-m-d', '2000-04-10'))
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
    public function testFindMeetingsForCurrentDateWithInvalidDate(): void
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //assert
        $this->expectException(Exception::class);
        $model->findMeetingsForCurrentDate(
            $faker->randomElement(['a', 1])
        )->all();
    }

    /**
     * Проверка: Метод поиска подходящих собраний вернет массив
     * собраний, если передать расписание собраний и дату проведения
     * собраний
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithValidData(): void
    {
        //arrange
        $meetingModel = new Meeting();
        $timetableModel = new Timetable();
        $timetableRecords = ArrayHelper::getColumn(
            $timetableModel
                ->findRecordsForEmployee(1)
                ->all(),
            'meeting_id'
        );

        //act
        $meetings = $meetingModel
            ->findAvailableMeetings($timetableRecords, '2023-12-04');

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
        $meetingModel = new Meeting();
        $timetableModel = new Timetable();
        $timetableRecords = ArrayHelper::getColumn(
            $timetableModel
                ->findRecordsForEmployee(1)
                ->all(),
            'meeting_id'
        );

        $allMeetings =   $meetingModel
            ->findMeetingsForCurrentDate('2023-12-04')
            ->all();

        $meetingTitles = ArrayHelper::getColumn($allMeetings, 'title');
        Verify::Array($meetingTitles)
            ->contains('Собрание 1');

        //act
        $meetings = $meetingModel
            ->findAvailableMeetings($timetableRecords, '2023-12-04');

        $meetingTitles = ArrayHelper::getColumn($meetings, 'title');

        //assert
        verify($meetings)->notNull();
        verify($meetings)->isArray();

        Verify::Array($meetingTitles)
            ->notContains('Собрание 1');
    }

    /**
     * Проверка: Метод поиска подходящих собраний вернет null,
     * если передать дату, на которую нет собраний
     *
     * @return void
     */
    public function testFindAvailableMeetingsWithNotActualDate(): void
    {
        //arrange
        $meetingModel = new Meeting();
        $timetableModel = new Timetable();
        $faker = Factory::create();

        $timetableRecords = ArrayHelper::getColumn(
            $timetableModel
                ->findRecordsForEmployee($faker->numberBetween(1, 5))
                ->all(),
            'meeting_id'
        );

        //act
        $meetings = $meetingModel
            ->findAvailableMeetings(
                $timetableRecords,
                $faker->date('Y-m-d', '2000-04-10')
            );

        //assert
        verify($meetings)->null();
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
        $meetingModel = new Meeting();
        $timetableModel = new Timetable();
        $faker = Factory::create();

        $timetableRecords = ArrayHelper::getColumn(
            $timetableModel
                ->findRecordsForEmployee($faker->numberBetween(1, 5))
                ->all(),
            'meeting_id'
        );

        //assert
        $this->expectException(Exception::class);

        $meetings = $meetingModel
            ->findAvailableMeetings(
                $timetableRecords,
                $faker->text()
            );

        verify($meetings)->null();
    }
}
