<?php

/**
 * @var $faker Faker\Factory
 * @var $index integer
 */

$faker = Faker\Factory::create('ru_RU');

$mockPatronymic = [
    'Иванович',
    'Петрович',
    'Михалыч',
    'Семеныч',
    ''
];

return [
    'firstname' => $faker->firstname,
    'lastname' => $faker->lastName,
    'patronymic' => $mockPatronymic[$faker->numberBetween(0, 4)],
    'login' => $faker->email
];
