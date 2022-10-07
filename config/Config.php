<?php

use Solve\Env;

return [
    'Database' => [
        'default' => Env::get('DATABASE_DEFAULT'),
        'mysql' => [
            'dbms' => 'mysql',
            'host' => Env::get('DATABASE_MYSQL_HOST'),
            'port' => '3306',
            'dbname' => Env::get('DATABASE_MYSQL_DBNAME'),
            'user' => Env::get('DATABASE_MYSQL_USER'),
            'pwd' => Env::get('DATABASE_MYSQL_PWD'),
            'charset' => 'utf8',
            'prefix' => '',
            'pdoAttr' => array()
        ],
    ]
];