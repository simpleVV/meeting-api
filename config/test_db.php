<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$testDbName = $_ENV['TEST_DB_NAME'];

$db['dsn'] = "mysql:host={$localHost};dbname={$testDbName}";

return $db;
