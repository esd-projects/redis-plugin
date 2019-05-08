<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/8
 * Time: 10:07
 */

namespace GoSwoole\Plugins\Redis;


class RedisProxy
{
    use GetRedis;
    public function __get($name)
    {
        return $this->redis()->$name;
    }

    public function __set($name, $value)
    {
        $this->redis()->$name = $value;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->redis(), $name], $arguments);
    }
}