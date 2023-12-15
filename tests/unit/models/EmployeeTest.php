<?php

namespace tests\unit\models;

use Codeception\Test\Unit;
use Faker\Factory;

use app\models\Employee;

class EmployeeTest extends Unit
{
    /**
     * Проверка на валидность полей модели Employee.
     *
     * @return void
     */
    public function testValidation(): void
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
        $this->assertFalse($model->validate(['firstname']));
        //act
        $model->setAttribute('firstname', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['firstname']));
        //act
        $model->setAttribute('firstname', $faker->realTextBetween(160, 200));
        //assert
        $this->assertFalse($model->validate(['firstname']));
        //act
        $model->setAttribute('lastname', $faker->lastName());
        //assert
        $this->assertTrue($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', null);
        //assert
        $this->assertFalse($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', $faker->numberBetween(1, 5));
        //assert
        $this->assertFalse($model->validate(['lastname']));
        //act
        $model->setAttribute('lastname', $faker->realTextBetween(160, 200));
        //assert
        $this->assertFalse($model->validate(['lastname']));
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
        $model->setAttribute('login', $faker->realTextBetween(160, 200));
        //assert
        $this->assertFalse($model->validate(['login']));
    }
}
