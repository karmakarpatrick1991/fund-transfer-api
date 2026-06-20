<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisService
{
    private \Predis\Client $redis;

    public function __construct()
    {
        try {
            $this->redis = RedisAdapter::createConnection(
                $_ENV['REDIS_URL']
            );
        }catch (\Throwable $e){
            var_dump($e->getMessage());
            die;
        }
    }

    public function get(string $key): mixed
    {
        return $this->redis->get($key);
    }

    public function set(string $key, mixed $value, int $ttl = 86400): void
    {
        $this->redis->setEx(
            $key,
            $ttl,
            is_array($value) || is_object($value) ? json_encode($value) : (string)$value
        );
    }

    public function delete(string $key): void
    {
        $this->redis->del($key);
    }
}
