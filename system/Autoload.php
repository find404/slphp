<?php

namespace system;


/**
 * 自动载入类
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月26日 11:56:16
 * @version     0.1.0 版本号
 */
class Autoload
{

    /**
     * 注册自动载入
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 11:54:45
     */
    public static function register()
    {
        spl_autoload_register('system\Autoload::autoload');
    }

    /**
     * 自定义加载类
     * @param string $className 调用的类名
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 11:56:54
     */
    public static function autoload($className)
    {
        if (!$className) {
            exit('自动载入Error: 类名无效');
        }

        switch (true) {
            case strpos($className, '\Controller\\') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'application' . APP_SEGEMENT_SYMBOL . $_SERVER['urlProject'] . APP_SEGEMENT_SYMBOL . 'Controller' . APP_SEGEMENT_SYMBOL . $_SERVER['urlController'] . APP_SUFFIX;
                break;
            case strpos($className, '\Model\\') !== false:
                //$modelNme = strstr($className, '\Model\\');
                //$filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'application' . APP_SEGEMENT_SYMBOL . $_SERVER['urlProject'] . $modelNme . APP_SUFFIX;
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . $className. APP_SUFFIX;
                break;
            case strpos($className, 'Load') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'Load' . APP_SUFFIX;
                break;
            case strpos($className, '\redis') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'Database' . APP_SEGEMENT_SYMBOL . 'redis' . APP_SEGEMENT_SYMBOL . 'redis' . APP_SUFFIX;
                break;
            case strpos($className, '\MDB') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'Database' . APP_SEGEMENT_SYMBOL . 'mysql' . APP_SEGEMENT_SYMBOL . 'MDB' . APP_SUFFIX;
                break;
            case strpos($className, '\PDB') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'Database' . APP_SEGEMENT_SYMBOL . 'postgresql' . APP_SEGEMENT_SYMBOL . 'PDB' . APP_SUFFIX;
                break;
            case strpos($className, '\coreseek') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'SearchEngines' . APP_SEGEMENT_SYMBOL . 'coreseek' . APP_SEGEMENT_SYMBOL . 'coreseek' . APP_SUFFIX;
                break;
            case strpos($className, 'SphinxClient') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'SearchEngines' . APP_SEGEMENT_SYMBOL . 'coreseek' . APP_SEGEMENT_SYMBOL . 'SphinxClient' . APP_SUFFIX;
                break;
            case strpos($className, '\LocalLog') !== false:
                $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'Log' . APP_SEGEMENT_SYMBOL . 'LocalLog' . APP_SUFFIX;
                break;
            default:
                $filename = APP_PATH . $className . APP_SUFFIX;
        }
		$filename = str_replace("\\",APP_SEGEMENT_SYMBOL,$filename);
        if (file_exists($filename)) {
            include($filename);
        } else {
            exit('自动载入Error:' . $className . ' loading Failed');
        }
    }


}