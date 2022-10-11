<?php

require __DIR__ . '/vendor/autoload.php';

//$token = 'solve';
//
////签名函数
//function sign()
//{
//    $params['timestamp'] = time();
//    global $token;
//    ksort($params);
//    $str = '';
//    foreach ($params as $key => $value) {
//        $str .= $key . '=' . $value;
//    }
//    $params['sign'] = sha1($str . 'token' . $token);
//    return http_build_query($params);
//}
//
//$a = sign();
//
//$http = new \Solve\Curl();
//$users = $http->get('localhost/v1/users' . $a);
//dump(json_decode($users));

Solve\Run::run('env/localhost');