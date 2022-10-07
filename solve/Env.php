<?php

namespace Solve;

class Env
{
    const ENV_PREFIX = 'PHP_';

    /**
     * 加载ENV配置文件
     * @access public
     * @param $filePath
     * @return void
     */
    public static function run($filePath): void
    {
        $env = parse_ini_file($filePath, true);
        foreach ($env as $key => $val) {
            $prefix = static::ENV_PREFIX . strtoupper($key);
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $item = $prefix . '_' . strtoupper($k);
                    putenv("$item=$v");
                }
            } else {
                putenv("$prefix=$val");
            }
        }
    }

    /**
     * 获取环境变量
     * @access public
     * @param string $name 环境变量名（支持二级 . 号分割）
     * @param string $default
     * @return string|array
     */
    public static function get(string $name, string $default = ''): string|array
    {
        $result = getenv(static::ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));
        if ($result) {
            return $result;
        } else {
            return $default;
        }
    }

    /**
     * 功能：设置环境变量
     * @param string $name
     * @param $val
     * @return array|false|string
     */
    #[NoReturn] public static function set(string $name, $val): bool|array|string
    {
        $prefix = static::ENV_PREFIX . strtoupper($name);
        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $item = $prefix . '_' . strtoupper($k);
                putenv("$item=$v");
            }
        } else {
            putenv("$prefix=$val");
        }
        return Env::get($name);
    }
}
