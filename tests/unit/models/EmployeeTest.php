<?php

namespace tests\unit\models;

use Codeception\Test\Unit;
use Faker\Factory;

use app\models\Employee;

class EmployeeTest extends Unit
{
    /**
     * Проверка на валидность параметра firstname модели Employee.
     *
     * @return void
     */
    public function testValidateFirstname()
    {
        //arrange
        $model = new Employee();
        $faker = Factory::create();

        //act
        $model->setAttribute('firstname', $faker->firstName());
        //assert
        $this->assertTrue($model->validate(['firstname']));
        //act
        $model->setAttribute('firstname', null);
        //assert
        $this->assertTrue($model->validate(['lastname']));
        //act
        $model->setAttribute('firstname', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['firstname']));
        //act
        $model->setAttribute('firstname', $faker->realTextBetween(256, 300));
        //assert
        $this->assertFalse($model->validate(['firstname']));
    }

    /**
     * Проверка на валидность параметра lastname модели Employee.
     *
     * @return void
     */
    public function testValidateLastname()
    {
        //arrange
        $model = new Employee();
        $faker = Factory::create();

        //act
        $model->setAttribute('lastname', $faker->lastName());
        //assert
        $this->assertTrue($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', null);
        //assert
        $this->assertTrue($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', $faker->realTextBetween(256, 300));
        //assert
        $this->assertFalse($model->validate(['lastname']));
    }


    /**
     * Проверка на валидность параметра login модели Employee.
     *
     * @return void
     */
    public function testValidateLogin()
    {
        //arrange
        $model = new Employee();
        $faker = Factory::create();

        //act
        $model->setAttribute('login', $faker->email());
        //assert
        $this->assertTrue($model->validate(['login']));
        //act
        $model->setAttribute('login', null);
        //assert
        $this->assertFalse($model->validate(['login']));
        //act
        $model->setAttribute('login', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['login']));
        //act
        $model->setAttribute('login', $faker->realTextBetween(256, 300));
        //assert
        $this->assertFalse($model->validate(['login']));
    }
}
