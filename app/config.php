<?php

return array(
    'database' => array(
        'name' => 'liveticket',
        'username' => 'root',
        'password' => '',
        'connection' => 'mysql:host=localhost',
        'options' => array(
            PDO:: MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO:: ATTR_ERRMODE => PDO:: ERRMODE_EXCEPTION ,
            PDO:: ATTR_PERSISTENT => true
        )
    ),
    'logs' => array(
        'name' => 'Registro Error',
        'file' => '../logs/liveticket-errors.log'
    ),
    'security' => array(
        'roles' => array(
            'ROLE_ADMINISTRADOR'=>4,
            'ROLE_GESTOR'=>3,
            'ROLE_COMPRADOR'=>2,
            'ROLE_ANONIMO'=>1
        )
    )
);