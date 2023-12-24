<?php

use Faker\Factory;

/**
 * @var $faker Faker\Factory
 * @var $index integer
 */

$faker = Factory::create('ru_RU');

$startTime = [
    '09:00',
    '09:10',
    '12:20',
    '14:30',
    '14:50'
];

$endTime = [
    '09:15',
    '12:00',
    '13:00',
    '15:00',
    '16:00'
];

$titleCount = $index + 1;

return [
    'title' => "Собрание {$titleCount}",
    'meeting_date' => '2023-12-20',
    'start_time' => $startTime[$index],
    'end_time' => $endTime[$index]
];
