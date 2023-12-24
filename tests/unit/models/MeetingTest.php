<?php

namespace tests\unit\models;

use Codeception\Test\Unit;
use Faker\Factory;

use app\models\Meeting;

class MeetingTest extends Unit
{
    /**
     * Проверка на валидность параметра title модели Meeting
     *
     * @return void
     */
    public function testValidateTitle()
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
        $model->setAttribute('title', $faker->realTextBetween(256, 300));
        //assert
        $this->assertFalse($model->validate(['title']));
    }

    /**
     * Проверка на валидность параметра date модели Meeting
     *
     * @return void
     */
    public function testValidateDate()
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //act
        $model->setAttribute('meeting_date', $faker->date('Y-m-d', 'now'));
        //assert
        $this->assertTrue($model->validate(['meeting_date']));
        //act
        $model->setAttribute('meeting_date', $faker->date('Y-m-d: H:i:s', 'now'));
        //assert
        $this->assertFalse($model->validate(['meeting_date']));
        //act
        $model->setAttribute('meeting_date', null);
        //assert
        $this->assertFalse($model->validate(['meeting_date']));
        //act
        $model->setAttribute('meeting_date', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['meeting_date']));
    }

    /**
     * Проверка на валидность параметра start_time модели Meeting
     *
     * @return void
     */
    public function testValidateStartTime()
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //act
        $model->setAttribute('start_time', $faker->date('H:i:s', 'now'));
        //assert
        $this->assertTrue($model->validate(['start_time']));
        //act
        $model->setAttribute('start_time', null);
        //assert
        $this->assertFalse($model->validate(['start_time']));
        //act
        $model->setAttribute('start_time', $faker->date('Y-m-d', 'now'));
        //assert
        $this->assertFalse($model->validate(['start_time']));
        //act
        $model->setAttribute('start_time', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['start_time']));
        //act
        $model->setAttribute('start_time', null);
        //assert
        $this->assertFalse($model->validate(['start_time']));
    }

    /**
     * Проверка на валидность параметра end_time модели Meeting
     *
     * @return void
     */
    public function testValidateEndTime()
    {
        //arrange
        $model = new Meeting();
        $faker = Factory::create();

        //act
        $model->setAttribute('end_time', $faker->date('H:i:s', 'now'));
        //assert
        $this->assertTrue($model->validate(['end_time']));
        //act
        $model->setAttribute('end_time', $faker->date('Y-m-d', 'now'));
        //assert
        $this->assertFalse($model->validate(['end_time']));
        //act
        $model->setAttribute('end_time', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['end_time']));
        //act
        $model->setAttribute('end_time', null);
        //assert
        $this->assertFalse($model->validate(['end_time']));
    }
}
