<?php

/**
 * @var $faker Faker\Factory
 * @var $index integer
 */

$faker = Faker\Factory::create('ru_RU');

$mockDateStart = [
    '2023-12-04 09:00',
    '2023-12-04 09:10',
    '2023-12-04 12:20',
    '2023-12-04 14:30',
    '2023-12-04 14:50'
];

$mockDateEnd = [
    '2023-12-04 09:15',
    '2023-12-04 12:00',
    '2023-12-04 13:00',
    '2023-12-04 15:00',
    '2023-12-04 16:00'
];

$mockTitle = [
    'Собрание 1',
    'Собрание 2',
    'Собрание 3',
    'Собрание 4',
    'Собрание 5'
];

return [
    'title' => $mockTitle[$index],
    'dt_start' => $mockDateStart[$index],
    'dt_end' => $mockDateEnd[$index]
];
