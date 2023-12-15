<?php

/**
 * @var $faker Faker\Factory
 * @var $index integer
 */

$faker = Faker\Factory::create('ru_RU');

return [
    'meeting_id' => ++$index,
    'employee_id' => $faker->numberBetween(1, 5),
];
