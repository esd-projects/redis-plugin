<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:49
 */

namespace ESD\Plugins\Redis;


class RedisConfig
{
    /**
     * @var RedisOneConfig[]
     */
    protected $redisConfigs;

    /**
     * @return RedisOneConfig[]
     */
    public function getRedisConfigs(): array
    {
        return $this->redisConfigs;
    }

    /**
     * @param RedisOneConfig[] $redisConfigs
     */
    public function setRedisConfigs(array $redisConfigs): void
    {
        $this->redisConfigs = $redisConfigs;
    }

    public function addRedisOneConfig(RedisOneConfig $buildFromConfig)
    {
        $this->redisConfigs[$buildFromConfig->getName()] = $buildFromConfig;
    }
}