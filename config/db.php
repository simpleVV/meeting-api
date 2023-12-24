<?php

use yii\db\Connection;

$localHost = $_ENV['DB_HOST'];
$dbName = $_ENV['DB_NAME'];

return [
    'class' => Connection::class,
    'dsn' => "mysql:host={$localHost};dbname={$dbName}",
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
];
