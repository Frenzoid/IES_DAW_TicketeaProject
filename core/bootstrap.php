<?php

use liveticket\core\App;
use liveticket\core\database\Connection;
use liveticket\core\database\QueryBuilder;

session_start();

$config = require __DIR__ . '/../app/config.php';
App:: bind ('config', $config);

App:: bind ('database', new QueryBuilder(
    Connection:: make ($config['database'])
));