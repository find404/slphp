<?php

namespace system;


/**
 * 核心初始化类。
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月27日 10:19:29
 * @version     0.1.0 版本号
 */
class Application
{

    /**
     * 初始化人口
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:19:53
     */
    public static function init()
    {
        session_start();
        //设置头
        self::_setHeader();
        //自动载入函数
        self::_setAutoload();
        //载入系统配置文件
        self::_loadSysFile();
        //设置默认时区
        self::_setTimezone();
        //设置默认模板和语言
        self::_setTemplateLanguage();
        //过滤用户输入参数
        self::_filterInput();
        //设置路由
        self::_setRoute();
    }

    /**
     * 设置系统配置文件
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:13:15
     */
    private static function _loadSysFile()
    {
		$_SERVER['ENVIRONMENT'] = 'production';
        if (DEBUG) {
			$_SERVER['ENVIRONMENT'] = 'development';
        }

        Load::Config('init');
        Load::Config('database');
        Load::Config('hook');
        Load::Config('SearchEngines');
    }

    /**
     * 设置TCP头部
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:13:15
     */
    private static function _setHeader()
    {
        header('X-Power-by: aiphp ' . VERSION);
    }

    /**
     * 设置默认时区
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:13:15
     */
    private static function _setTimezone()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    /**
     * 设置载入函数
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:13:15
     */
    private static function _setAutoload()
    {
        require_once APP_PATH . 'system' . APP_SEGEMENT_SYMBOL . 'Autoload' . APP_SUFFIX;
        Autoload::register();
    }

    /**
     * 设置默认视图模板，和语言
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月27日 11:51:14
     */
    private static function _setTemplateLanguage()
    {
        $initConfig = Load::getConfig('init');
        $_SERVER['TEMPLATE'] = $initConfig['template'];
        if (isset($_GET['lang'])) {
            $_COOKIE['lang'] = $_GET['lang'];
            setcookie('lang', $_COOKIE['lang']);
        }
        $_SERVER['LANGUAGE'] = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : $initConfig['language'];
    }

    /**
    * 过滤用户输入参数
    * @author     zhy	find404@foxmail.com
    * @createTime 2018年10月12日 12:06:10
    */
    private static function _filterInput()
    {
        $_GET = filter_input_array(INPUT_GET,$_GET);
        $_POST = filter_input_array(INPUT_POST,$_POST);
    }

    /**
     * 设置路由
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:13:15
     */
    private static function _setRoute()
    {
        Route::parse();
    }


}
 