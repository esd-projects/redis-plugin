<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/22
 * Time: 14:36
 */

use ESD\Core\Channel\Channel;
use ESD\Core\DI\DI;
use ESD\Core\Plugins\Event\EventCall;
use ESD\Coroutine\Channel\ChannelFactory;
use ESD\Coroutine\Co;
use ESD\Coroutine\Event\EventCallFactory;
use ESD\Plugins\Redis\RedisConfig;
use ESD\Plugins\Redis\RedisPool;

require __DIR__ . '/../vendor/autoload.php';

Co::enableCo();
enableRuntimeCoroutine();
DI::$definitions = [
    Channel::class => new ChannelFactory(),
    EventCall::class => new EventCallFactory()
];

goWithContext(function () {
    //$redisConfig = new RedisConfig('redis-master.dev.svc.cluster.local');
    $redisConfig = new RedisConfig('127.0.0.1');
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