<?php

namespace Solve;

class Register
{
    public static function run(): void
    {
        self::timeZone();
        self::constant();
        self::error();
    }

    public static function timeZone(): void
    {
        date_default_timezone_set('Asia/Shanghai'); //设置上海时区
    }

    public static function constant(): void
    {
        define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    }

    public static function error(): void
    {
        //注册一个会在php中止时执行的函数
        register_shutdown_function('Solve\Error::error_end');
        //注册用户自定义错误处理方法
        set_error_handler('Solve\Error::error_handler');
    }


}