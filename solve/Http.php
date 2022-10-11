<?php

namespace Solve;

class Http
{
    private function curl(string $url, mixed $params = [], string $method = ''): bool|string
    {
        $ch = curl_init();
        //把 curl_exec() 结果转换为字符串，而不是直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
            case 'PUT':
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_URL, $url);
                break;
            case 'GET':
                if ($params and is_array($params)) {
                    curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
                } else {
                    curl_setopt($ch, CURLOPT_URL, $url);
                }
                break;
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 发送 get 请求
     * @param string $url
     * @param mixed $params
     * @return bool|string
     */
    public function get(string $url, mixed $params = false): bool|string
    {
        return $this->curl($url, $params, 'GET');
    }

    /**
     * 发送 post 请求
     * @param string $url
     * @param mixed $params
     * @return bool|string
     */
    public function post(string $url, mixed $params = false): bool|string
    {
        return $this->curl($url, $params, 'POST');
    }

    /**
     * 发送 put 请求
     * @param string $url
     * @param mixed $params
     * @return bool|string
     */
    public function put(string $url, mixed $params = false): bool|string
    {
        return $this->curl($url, $params, 'PUT');
    }

    /**
     * 发送 delete 请求
     * @param string $url
     * @param mixed $params
     * @return bool|string
     */
    public function delete(string $url, mixed $params = false): bool|string
    {
        return $this->curl($url, $params, 'DELETE');
    }

}