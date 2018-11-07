<?php

namespace system;

/**
 * redis 缓存类（基于redis-cluster）
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年10月29日 17:46:19
 * @version     0.1.0 版本号
 */
class redis
{

    /**
     * 当前键
     * @var string
     */
    private static $key = '';

    /**
     * 当前值
     * @var string
     */
    private static $value = '';

    /**
     * 当前默认生存时间(60*60*24)
     * @var int
     */
    private static $expire = 86400;

    /**
     * 默认数据库
     * @var
     */
    private static $dbindex = 0;

    /**
     * 当前写操作表
     * @var
     */
    private static $connectionsIndex = 0;

    /**
     * 数据库连接
     * @var object
     */
    private static $connections = [];


    /**
     * 当前连接的redis   IP后缀_端口
     * @var string
     */
    private static $result = false;

    /**
     * 预留主从库
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:40:12
     * @return bool
     */
    private static function getConnection()
    {
        if (self::$connections == null) {
            if (!extension_loaded('redis')) {
                exit('当前未安装redis扩展！');
            }
            $databaseConfig = Load::getConfig('database');
            if (!isset($databaseConfig['redis'])) {
                exit('数据库配置redis数组未设置');
            }

            foreach ($databaseConfig['redis'] as $key => $val) {
                self::$connections[$key] = new \redis();
                if (self::$connections[$key]->connect($val['host'], $val['port'])) {
                    if ($val['database']) {
                        self::$connections[$key]->select($val['database']);
                    }
                    if ($val['auth']) {
                        self::$connections[$key]->auth($val['auth']);
                    }
                } else {
                    unset(self::$connections[$key]);
                }
            }
        }
        return;
    }

    /**
     * 检测redis键是否存在
     * @param $key string    存储redis的键
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:44:42
     * @return bool
     */
    public static function exists($key)
    {
        self::processKey($key);
        if (self::getConnection()->exists(self::$key)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取redis键的值
     * @param $key string    存储redis的键
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:43:58
     * @return bool
     */
    public static function get($key)
    {
        self::processKey($key);
        self::$result = [];
        self::structuralGrammar(function ($value) {
            if ($value->exists(self::$key)) {
                self::$result = json_decode($value->get(self::$key), true);
                return;
            }
        });
        return self::$result;
    }

    /**
     * 带生存时间写入key
     * @param $key string    存储redis的键
     * @param $value string    存储redis的值
     * @param $expire int    存储redis的键的销毁时间
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:41:54
     * @return bool
     */
    public static function setex($key, $value, $expire)
    {
        self::processKey($key);
        self::processValue($value);
        self::processExpire($expire);

        self::$result = [];
        self::structuralGrammar(function ($value) {
            if ($value->setex(self::$key, self::$expire, json_encode(self::$value))) {
                self::$result = true;
                return;
            }
        });
        return self::$result;
    }

    /**
     * 设置redis键值(没有过期时间)
     * @param $key string    存储redis的键
     * @param $value string    存储redis的值
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:47:08
     * @return bool
     */
    public static function set($key, $value)
    {
        self::processKey($key);
        self::processValue($value);

        self::structuralGrammar(function ($value) {
            if ($value->set(self::$key, json_encode(self::$value))) {
                self::$result = true;
                return;
            }
        });
        return self::$result;
    }

    /**
     * 获取key生存时间
     * @param $key string    存储redis的键
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:55:35
     * @return bool
     */
    public static function ttl($key)
    {
        self::processKey($key);
        return self::getConnection()->ttl(self::$key);
    }

    /**
     * 删除key
     * @param $key string    存储redis的键
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:55:54
     * @return bool
     */
    public static function del($key)
    {
        self::processKey($key);
        return self::getConnection()->del(self::$key);
    }

    /**
     * 清空所有数据
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:57:05
     * @return bool
     */
    public static function flushall()
    {
        return self::getConnection()->flushall();
    }

    /**
     * 获取所有key
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:57:20
     * @return bool
     */
    public static function keys()
    {
        return self::getConnection()->keys('*');
    }


    /**
     * 预留处理，校验key安全。
     * @param $key string    存储redis的键
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:38:28
     */
    private static function processKey($key)
    {
        self::$key = $key;
    }

    /**
     * 预留处理，校验value安全。
     * @param $value string    存储redis的值
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:38:28
     */
    private static function processValue($value)
    {
        self::$value = $value;
    }

    /**
     * 预留处理，校验value安全。
     * @param $value string    存储redis的值
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月30日 13:38:28
     */
    private static function processExpire($expire)
    {
        self::$expire = 60 * 60 * $expire;
    }

    /**
     * 结构体语法
     * @param $function string    匿名函数结构体
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 15:10:33
     */
    private static function structuralGrammar($function)
    {
        self::getConnection();
        foreach (self::$connections as $key => $val) {
            $function($val);
        }
    }

}