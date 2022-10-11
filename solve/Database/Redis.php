<?php

namespace Solve\Database;

class Redis extends DataBase
{
    public \Redis $redis;

    public function __construct(string $host = '127.0.0.1', string $port = '6378')
    {
        try {
            $this->redis = new \Redis();
            $this->redis->connect($host, $port);
            return $this->redis;
        } catch (\RedisException $e) {
            $this->error($e);
        }
        return false;
    }

    public function ping($host): bool|string
    {
        try {
            return $this->redis->ping($host);
        } catch (\RedisException $e) {
            $this->error($e);
        }
        return false;
    }

}