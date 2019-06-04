<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/6/4
 * Time: 18:31
 */

namespace ESD\Plugins\Redis;


class Redis
{
    /**
     * @var \Redis
     */
    private $_redis;

    public function __construct()
    {
        $this->_redis = new \Redis();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->_redis, $name], $arguments);
    }

    public function __set($name, $value)
    {
        $this->_redis->$name = $value;
    }

    public function __get($name)
    {
        return $this->_redis->$name;
    }
}