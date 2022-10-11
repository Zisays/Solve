<?php

namespace Solve;

class Route
{
    //允许请求的方法
    private static array $_allowMethod = ['GET', 'POST', 'PUT', 'DELETE'];
    //返回的状态码
    private static array $_statusCode = [
        200 => 'OK',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error'
    ];

    /**
     * 运行
     * @return void
     */
    public static function run(): void
    {
        include_once 'config/api.php';
        self::error(404, '请求的 Api 接口不存在');
    }

    /**
     * 注册路由
     * @param string $methods
     * @param string $url
     * @param string $action
     * @param string $version
     * @return void
     */
    private static function addRoute(string $methods, string $url, string $action, string $version = 'v1'): void
    {
        if (in_array($methods, self::$_allowMethod) and $methods == $_SERVER['REQUEST_METHOD']) {
            $param = self::handle($methods, $url, $version);
            if (!empty($param)) {
                $action = explode('@', $action);
                $file = '\Api\\' . $version . '\\' . $action[0];
                $function = $action[1];
                $controller = new $file;

                if ($param == '') {
                    $controller->$function();
                } else {
                    $controller->$function($param, $filter = '');
                }
            }
        }
    }

    public static function handle($methods, $url, $version)
    {
        $pathInfo = trim($_SERVER['PATH_INFO'], '/');
        $uri = preg_replace('/({\w+})/', '(\d+)', trim($url, '/'));
        $preg = '/^' . $version . '\/' . preg_replace('/\//', '\/', $uri) . '$/';
        if (preg_match($preg, $pathInfo, $arr)) {
            $param = '';
            switch ($methods) {
                case 'DELETE':
                case 'GET':
                    $arr = explode('/', $arr[0]);
                    array_shift($arr);
                    $param = $arr;
                    break;
                case 'PUT':
                case 'POST':
                    $param = json_decode(file_get_contents('php://input'), true);
                    break;
            }
            return $param;
        } else {
            return false;
        }
    }


    public static function get($url, $action = null, $version = 'v1'): void
    {
        self::addRoute('GET', $url, $action, $version);
    }

    public static function post($url, $action = null, $version = 'v1'): void
    {
        self::addRoute('POST', $url, $action, $version);
    }

    public static function put($url, $action = null, $version = 'v1'): void
    {
        self::addRoute('PUT', $url, $action, $version);
    }

    public static function delete($url, $action = null, $version = 'v1'): void
    {
        self::addRoute('DELETE', $url, $action, $version);
    }

    /**
     * 返回一个成功地响应数据方法和格式
     * @param array $data
     * @param string $msg
     * @return void
     */
    public static function success(array $data = [], string $msg = 'success'): void
    {
        self::_json($data, 200, $msg);
    }

    /**
     * 返回一个错误地响应数据方法和格式
     * @param int $code
     * @param string $msg
     * @return void
     */
    public static function error(int $code = 400, string $msg = 'error'): void
    {
        if ($msg == 'error' and !empty(self::$_statusCode[$code])) {
            $msg = self::$_statusCode[$code];
        }
        self::_json([], $code, $msg);
    }

    /**
     * 输出 JSON
     * @param array $data
     * @param int $code
     * @param string $msg
     * @return void
     */
    private static function _json(array $data = [], int $code = 200, string $msg = ''): void
    {
        if ($code !== 200 and $code > 200) {
            header('HTTP/1.1 ' . $code . ' ' . self::$_statusCode[$code]);
        }
        header("Content-Type:application/json;charset=utf-8");
        $response = ['code' => $code, 'msg' => $msg, 'time' => time(), 'data' => $data];
        exit(json_encode($response));
    }


}