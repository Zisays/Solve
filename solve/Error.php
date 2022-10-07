<?php

namespace Solve;

use Exception;

class Error
{
    /**
     * 调试模式（true:开，false：关）
     * @return void
     */
    public static function debug(): void
    {
        if (ENV::get('DEBUG')) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
        }
    }

    /**
     * 设置用户自定义的错误处理函数
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     */
    public static function error_handler(int $errno, string $errstr, string $errfile = '?', int $errline = 0): void
    {
        include ROOT . '/solve/Page/error_handler.php';
    }

    /**
     * 设置用户自定义运行结束时的错误
     * @return void
     * @throws Exception
     */
    public static function error_end(): void
    {
        $e = error_get_last();
        $errType = match (error_get_last()) {
            1 => '致命的运行时错误',
            2 => '运行时警告 (非致命错误)',
            4 => '编译时语法解析错误',
            8, 8192 => '运行时通知',
            16 => '在 PHP 初始化启动过程中发生的致命错误',
            32 => 'PHP 初始化启动过程中发生的警告 (非致命错误)',
            64 => '致命编译时错误',
            128 => '编译时警告 (非致命错误)',
            256 => '用户产生的错误信息',
            512, 16384 => '用户产生的警告信息',
            1024 => '用户产生的通知信息',
            2048 => '启用 PHP 对代码的修改建议，以确保代码具有最佳的互操作性和向前兼容性。',
            4096 => '可被捕捉的致命错误',
            default => false
        };
        if ($errType !== false) {
            throw new Exception($e, $errType);
        }
    }


}