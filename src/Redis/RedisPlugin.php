<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/23
 * Time: 10:30
 */

namespace ESD\Plugins\Redis;


use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\Plugins\Logger\GetLogger;
use ESD\Core\Server\Server;

class RedisPlugin extends AbstractPlugin
{
    use GetLogger;
    /**
     * @var RedisConfig
     */
    protected $redisConfig;


    public function __construct()
    {
        parent::__construct();
        $this->redisConfig = new RedisConfig();
        $this->redisConfig->setRedisConfigs([]);
    }

    /**
     * 获取插件名字
     * @return string
     */
    public function getName(): string
    {
        return "Redis";
    }

    /**
     * 在服务启动前
     * @param Context $context
     * @return mixed
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \Exception
     */
    public function beforeServerStart(Context $context)
    {
        ini_set('default_socket_timeout', '-1');
        //所有配置合併
        foreach ($this->redisConfig->getRedisConfigs() as $config) {
            $config->merge();
        }
        $configs = Server::$instance->getConfigContext()->get(RedisOneConfig::key, []);
        foreach ($configs as $key => $value) {
            $redisOneConfig = new RedisOneConfig("");
            $redisOneConfig->setName($key);
            $this->redisConfig->addRedisOneConfig($redisOneConfig->buildFromConfig($value));
        }
        $redisProxy = new RedisProxy();
        $this->setToDIContainer(\Redis::class, $redisProxy);
        $this->setToDIContainer(Redis::class, $redisProxy);
        $this->setToDIContainer(RedisConfig::class, $this->redisConfig);
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     * @throws RedisException
     */
    public function beforeProcessStart(Context $context)
    {
        ini_set('default_socket_timeout', '-1');
        $redisManyPool = new RedisManyPool();
        if (empty($this->redisConfig->getRedisConfigs())) {
            $this->warn("没有redis配置");
        }
        foreach ($this->redisConfig->getRedisConfigs() as $key => $value) {
            $redisPool = new RedisPool($value);
            $redisManyPool->addPool($redisPool);
            $this->debug("已添加名为 {$value->getName()} 的Redis连接池");
        }
        $context->add("redisPool", $redisManyPool);
        $this->setToDIContainer(RedisManyPool::class, $redisManyPool);
        $this->setToDIContainer(RedisPool::class, $redisManyPool->getPool());
        $this->ready();
    }

    /**
     * @return RedisOneConfig[]
     */
    public function getConfigList(): array
    {
        return $this->redisConfig->getRedisConfigs();
    }

    /**
     * @param RedisOneConfig[] $configList
     */
    public function setConfigList(array $configList): void
    {
        $this->redisConfig->setRedisConfigs($configList);
    }

    /**
     * @param RedisOneConfig $redisOneConfig
     */
    public function addConfigList(RedisOneConfig $redisOneConfig): void
    {
        $this->redisConfig->addRedisOneConfig($redisOneConfig);
    }
}