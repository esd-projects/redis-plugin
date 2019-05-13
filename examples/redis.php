<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/22
 * Time: 14:36
 */

use ESD\Plugins\Redis\RedisConfig;
use ESD\Plugins\Redis\RedisPool;

require __DIR__ . '/../vendor/autoload.php';

enableRuntimeCoroutine();

goWithContext(function () {
    $redisConfig = new RedisConfig('redis-master.dev.svc.cluster.local');
    $redisPool = new RedisPool($redisConfig);
    setContextValue("redisPool", $redisPool);

    goWithContext(function () {
        $redisPool = getDeepContextValueByClassName(RedisPool::class);
        $db = $redisPool->db();
        var_dump($db->get("test"));
    });

    goWithContext(function () {
        $redisPool = getDeepContextValueByClassName(RedisPool::class);
        $db = $redisPool->db();
        var_dump($db->get("test"));
    });
});