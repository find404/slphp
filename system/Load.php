<?php

namespace system;


/**
 * 加载类
 * 2018年9月25日 10:36:58
 * zhy    find404@foxmail.com
 */
class Load
{
    /**
     * 配置静态信息
     * @var Array
     */
    private static $_config = [];

    /**
     * 实例控制器内存标识
     * @var Array
     */
    private static $_controller = [];

    /**
     * 当前实例控制器
     * @var Array
     */
    private static $currentController = '';

    /**
     * 实例数据库模型内存标识
     * @var Array
     */
    private static $_model = [];

    /**
     * 实例服务模型内存标识
     * @var Array
     */
    private static $_server = [];


    /**
     * 视图层数据
     * @var Array
     */
    public static $_viewData = [];

    /**
     * 语言包
     * @var Array
     */
    private static $_lang = [];

    /**
     * 钩子函数映射关系
     * @var
     */
    private static $_hook = [];

    /**
     * 当前图片位置
     * @var string
     */
    private static $_img = null;

    /**
     * 当前JS位置
     * @var string
     */
    private static $_js = null;

    /**
     * 当前CSS位置
     * @var string
     */
    private static $_css = null;

    /**
     * 当前字体位置
     * @var string
     */
    private static $_font = null;

    /**
     * 加载config信息
     * @param string $filename 文件名
     * 2018年9月25日 10:36:58
     * zhy    find404@foxmail.com
     */
    public static function Config($filename)
    {
        $fileRouteName = APP_PATH . APP_SEGEMENT_SYMBOL . 'config' . APP_SEGEMENT_SYMBOL . $_SERVER['ENVIRONMENT'] . APP_SEGEMENT_SYMBOL . $filename . APP_SUFFIX;
        if (file_exists($fileRouteName)) {
            self::$_config[$filename] = require_once $fileRouteName;
        } else {
            exit('加载config信息 ，Error:' . $filename . ' loading Failed');
        }
    }


    /**
     * 获取配置文件
     * @param string $filename 文件名
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function getConfig($filename)
    {
        return self::$_config[$filename];
    }

    /**
     * 加载控制器信息
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function Controller()
    {
        //设置语言包
        self::Lang();
        self::$currentController = '\application\\' . $_SERVER['urlProject'] . '\Controller\\' . $_SERVER['urlController'];
        if (!isset(self::$_controller[self::$currentController])) {
            self::$_controller[self::$currentController] = new self::$currentController();
        }
        self::$_controller[self::$currentController]->$_SERVER['urlMethod']();
        //设置HOOK
        self::setHook();
    }

    /**
     * 加载数据库MODEL
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function Model($filename = null)
    {
        if ($filename) {
            $position = strpos($filename, '\\');
            if($position){
                $newController = '\application\\' . substr_replace($filename, '\Model', $position, 0);
            }else{
                $newController = '\application\\' . $_SERVER['urlProject'] . '\Model\\' . $filename;
            }
        } else {
            $newController = '\application\\' . $_SERVER['urlProject'] . '\Model\\' . $_SERVER['urlController'];
        }

        if (!isset(self::$_model[$newController])) {
            self::$_model[$newController] = new $newController();
        }
        return self::$_model[$newController];
    }

    /**
     * 加载视图
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function View($filename)
    {
        $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'application' . APP_SEGEMENT_SYMBOL . $_SERVER['urlProject'] . APP_SEGEMENT_SYMBOL . 'View' . APP_SEGEMENT_SYMBOL . $_SERVER['TEMPLATE'] . APP_SEGEMENT_SYMBOL . $filename . APP_SUFFIX;
        if (file_exists($filename)) {
            //后续会被替换，现在使用的是老版本的CSS/js/image加载路径
            self::$_css = APP_SEGEMENT_SYMBOL . 'themes' . APP_SEGEMENT_SYMBOL . $_SERVER['TEMPLATE'] . APP_SEGEMENT_SYMBOL . 'css' . APP_SEGEMENT_SYMBOL;
            self::$_js = APP_SEGEMENT_SYMBOL . 'themes' . APP_SEGEMENT_SYMBOL . $_SERVER['TEMPLATE'] . APP_SEGEMENT_SYMBOL . 'js' . APP_SEGEMENT_SYMBOL;
            self::$_img = APP_SEGEMENT_SYMBOL . 'themes' . APP_SEGEMENT_SYMBOL . $_SERVER['TEMPLATE'] . APP_SEGEMENT_SYMBOL . 'images' . APP_SEGEMENT_SYMBOL;
            include_once($filename);
        } else {
            exit('视图文件不存在！:' . $filename . ' loading Failed');
        }
    }


    /**
     * 加载HTML片段，静默处理，有则加载
     * @param $filename string 文件名
     * @throws
     * @author     zhy	find404@foxmail.com
     * @createTime 2018年10月8日 13:41:54
     */
    private static function includeViewSegment($filename)
    {
        $filename = APP_PATH . APP_SEGEMENT_SYMBOL . 'application' . APP_SEGEMENT_SYMBOL . $_SERVER['urlProject'] . APP_SEGEMENT_SYMBOL . 'View' . APP_SEGEMENT_SYMBOL . $_SERVER['TEMPLATE'] . APP_SEGEMENT_SYMBOL . $filename . APP_SUFFIX;
        if (file_exists($filename)) {
            include($filename);
        }
    }


    /**
     * 加载数据库MODEL
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function Service($filename = null)
    {
        if ($filename) {
            $position = strpos($filename, '\\');
            if($position){
                $newController = '\application\\' . substr_replace($filename, '\Service', $position, 0);
            }else{
                $newController = '\application\\' . $_SERVER['urlProject'] . '\Service\\' . $filename;
            }
        } else {
            $newController = '\application\\' . $_SERVER['urlProject'] . '\Service\\' . $_SERVER['urlController'];
        }

        if (!isset(self::$_server[$newController])) {
            self::$_server[$newController] = new $newController();
        }
        return self::$_server[$newController];
    }

    /**
     * 加载语言包
     * @todo 后续加载指定文件名
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月26日 15:10:27
     * @return bool
     */
    public static function Lang($filename = '')
    {
        if ($filename) {

        } else {
            $filename = $_SERVER['urlProject'] . '.' . $_SERVER['urlController'];
            $fileRouteName = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'language' . APP_SEGEMENT_SYMBOL . $_SERVER['LANGUAGE'] . APP_SEGEMENT_SYMBOL . $filename . APP_SUFFIX;
        }

        //无需报错
        if (file_exists($fileRouteName)) {
            self::$_lang[$filename] = require_once $fileRouteName;
        }
    }

    /**
     * 获取语言包
     * @todo 后续加载指定文件名
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月27日 14:47:34
     * @return bool
     */
    public static function getLang()
    {
        if (isset(self::$_lang[$_SERVER['urlProject'] . '.' . $_SERVER['urlController']])) {
            return self::$_lang[$_SERVER['urlProject'] . '.' . $_SERVER['urlController']];
        }
    }

    /**
     * 框架输出安全值方法
     * @todo 后续加载指定文件名
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月12日 17:43:40
     * @return bool
     */
    public static function aiecho($value)
    {
        echo htmlspecialchars($value);
    }

    /**
     * 框架coreseek转发器
     * @todo 后续加载指定文件名
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月23日 11:39:32
     * @return bool
     */
    public static function coreseek($value,$keyword,$index='')
    {
        $fileRouteName = APP_PATH . APP_SEGEMENT_SYMBOL . 'system' . APP_SEGEMENT_SYMBOL . 'SearchEngines' . APP_SEGEMENT_SYMBOL . 'coreseek' . APP_SEGEMENT_SYMBOL . 'relay' . APP_SUFFIX;
        if (file_exists($fileRouteName)) {
            include ($fileRouteName);
            return $value($keyword,$index);
        }
    }

    /**
     * 设置Hook方法
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月28日 10:16:15
     */
    private static function setHook()
    {
        if (!isset(self::$_config['hook'])) {
            return;
        }

        if (!isset(self::$_config['hook'][$_SERVER['urlProject']])) {
            return;
        }

        if (!isset(self::$_config['hook'][$_SERVER['urlProject']]['Controuller'])) {
            return;
        }

        if (!isset(self::$_config['hook'][$_SERVER['urlProject']]['Controuller'][$_SERVER['urlController']])) {
            return;
        }

        if (!isset(self::$_config['hook'][$_SERVER['urlProject']]['Controuller'][$_SERVER['urlController']][$_SERVER['urlMethod']])) {
            return;
        }

        if (!is_array(self::$_config['hook'][$_SERVER['urlProject']]['Controuller'][$_SERVER['urlController']][$_SERVER['urlMethod']])) {
            return;
        }

        foreach (self::$_config['hook'][$_SERVER['urlProject']]['Controuller'][$_SERVER['urlController']][$_SERVER['urlMethod']] as $val) {
            self::$_controller[self::$currentController]->$val();
        }
    }

}