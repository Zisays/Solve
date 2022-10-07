<?php

namespace Solve;

class Route
{
    public static function run(): void
    {
        $url = $_SERVER['PATH_INFO'];
        if ($url == '/' or empty($url)) {
            dump(json_encode(['code' => 404, 'msg' => '请求的 API 地址错误！'], JSON_UNESCAPED_UNICODE));
        } else {
            //Api 接口请求方式（"GET", "HEAD"，"POST"，"PUT"）
            $method = $_SERVER['REQUEST_METHOD'];
            //输出
            dump($method);
        }
    }
}