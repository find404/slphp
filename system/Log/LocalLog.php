<?php

namespace system;

/**
 * 日志模块
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月26日 18:09:34
 * @version     0.1.0 版本号
 */
class LocalLog
{

    /**
     * 写入日志
     * @param string $message 日志内容
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 18:08:11
     * @return bool
     */
    public static function write($message)
    {
        $message = '[' . date('Y-m-d H:i:s') .
            ' 项目：' . $_SERVER['urlProject'] . ' 控制器：' . $_SERVER['urlController'] . ' 方法：' . $_SERVER['urlMethod'] . ' ]'
            . $message . "\n";
        $fileDirectory = LOG_PATH . $_SERVER['urlProject'] . APP_SEGEMENT_SYMBOL . $_SERVER['urlController'] . APP_SEGEMENT_SYMBOL . $_SERVER['urlMethod'] . APP_SEGEMENT_SYMBOL;
        file_exists($fileDirectory) or mkdir($fileDirectory, 0777, true);
        file_put_contents($fileDirectory . date('Y-m-d') . '.log', $message, FILE_APPEND);
    }

}
