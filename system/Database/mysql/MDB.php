<?php

namespace system;

/**
 * 数据库操作驱动 , PDO方式，主从库
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月26日 14:57:04
 * @version     0.1.0 版本号
 */
class MDB
{
    /**
     * 数据库连接
     * @var object
     */
    private static $connections = [];

    /**
     * 当前合法SQL语句
     * @var string
     */
    private $sql = '';

    /**
     * 预留主从库
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:40:12
     * @return bool
     */
    private static function getConnection()
    {
        if (self::$connections == null) {
            $databaseConfig = Load::getConfig('database');

            if (!isset($databaseConfig['data'])) {
                exit('数据库配置DATA数组未设置');
            }
            if (!isset($databaseConfig['data']['mysql'])) {
                exit('数据库配置MYSQL数组未设置');
            }

            foreach ($databaseConfig['data']['mysql'] as $val) {
                self::$connections[] = new \PDO($val['driver'] . ':host=' . $val['host'] . ';dbname=' . $val['database'] . ';port=' . $val['port'] . ';charset=' . $val['charset'], $val['username'], $val['password'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_TIMEOUT => 1,
                ]);
            }
        }

        return self::$connections[0];
    }

    /**
     * 获取多条
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:42:47
     * @return bool
     */
    public function get($sql)
    {
        $this->processSql($sql);
        $dd = self::getConnection()->prepare($this->sql);
        $dd->execute();
        return $dd->fetchAll();
    }

    /**
     * 获取多条
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:44:25
     * @return bool
     */
    public function first($sql)
    {
        $this->processSql($sql);
        $dd = self::getConnection()->prepare($this->sql);
        $dd->execute();
        return $dd->fetch();
    }

    /**
     * 获取指定字段
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月10日 14:45:08
     * @return bool
     */
    public function value($sql)
    {
        $this->processSql($sql);
        $dd = self::getConnection()->prepare($this->sql);
        $dd->execute();
        return $dd->fetchColumn();
    }



    /**
     * 更新数据
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:44:25
     * @return bool
     */
    public function update($sql)
    {
        return self::getConnection()->exec($sql);
    }

    /**
     * 添加数据
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:44:25
     * @return bool
     */
    public function insert($sql)
    {
        return self::getConnection()->exec($sql);
    }

    /**
     * 删除数据
     * @param string $sql
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:44:25
     * @return bool
     */
    public function delete($sql)
    {
        return self::getConnection()->exec($sql);
    }

    /**
     * 预留处理，校验SQL安全。
     * @param $sql
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 16:44:25
     */
    private function processSql($sql)
    {
        $this->sql = $sql;
    }

}