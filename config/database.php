<?php

namespace Solve;

return [
    //默认数据库
    'default' => Env::get('DATABASE_DEFAULT'),
    //mysql 数据库
    'mysql' => [
        'dbms' => 'mysql',
        'host' => Env::get('MYSQL_HOST'),
        'port' => '3306',
        'dbname' => Env::get('MYSQL_DBNAME'),
        'user' => Env::get('MYSQL_USER'),
        'pwd' => Env::get('MYSQL_PWD'),
        'charset' => 'utf8',
        'prefix' => '',
        'pdoAttr' => array()
    ],
    // 声明一个 token ，在中间件中作为签名验证
    'token' => 'solve'
];