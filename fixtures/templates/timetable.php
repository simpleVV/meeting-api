<?php

use Faker\Factory;

/**
 * @var $faker Faker\Factory
 * @var $index integer
 */

$faker = Factory::create('ru_RU');

return [
    'meeting_id' => $index + 1,
    'employee_id' => 1,
];
