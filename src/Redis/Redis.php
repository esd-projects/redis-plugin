<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/6/4
 * Time: 18:31
 */

namespace ESD\Plugins\Redis;


use ESD\Psr\DB\DBInterface;

class Redis implements DBInterface
{
    /**
     * @var \Redis
     */
    private $_redis;

    private $_lastQuery;

    public function __construct()
    {
        $this->_redis = new \Redis();
    }

    public function __call($name, $arguments)
    {
        $this->_lastQuery = $name;
        return $this->execute($name, function () use ($name, $arguments) {
            return call_user_func_array([$this->_redis, $name], $arguments);
        });
    }

    public function __set($name, $value)
    {
        $this->_redis->$name = $value;
    }

    public function __get($name)
    {
        return $this->_redis->$name;
    }

    public function getType()
    {
        return "redis";
    }

    public function execute($name,callable $call = null)
    {
        if ($call != null) {
            return $call();
        }
    }

    public function getLastQuery()
    {
        return $this->_lastQuery;
    }
}