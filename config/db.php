<?php

$localhost = @$_ENV['DB_HOST'];
$dbname = @$_ENV['DB_NAME'];

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$localhost};dbname={$dbname}",
    'username' => @$_ENV['DB_USER'],
    'password' => @$_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
];
