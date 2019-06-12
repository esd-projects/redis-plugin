<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 17:55
 */

namespace ESD\Plugins\Redis;


trait GetRedis
{
    /**
     * @param string $name
     * @return mixed|\Redis
     * @throws RedisException
     */
    public function redis($name = "default")
    {
        $db = getContextValue("Redis:$name");
        if ($db == null) {
            /** @var RedisManyPool $redisPool */
            $redisPool = getDeepContextValueByClassName(RedisManyPool::class);
            $pool = $redisPool->getPool($name);
            if ($pool == null) throw new RedisException("没有找到名为{$name}的redis连接池");
            return $pool->db();
        } else {
            return $db;
        }
    }
}