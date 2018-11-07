<?php

namespace system;

/**
 * 路由类
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月25日 10:36:58
 * @version     0.1.0 版本号
 */
class Route
{

    /**
     * 当前URL模式
     * var string
     */
    private static $urlModel = null;

    /**
     * 当前URL指向项目文件夹
     * var string
     */
    private static $urlProject = null;

    /**
     * 当前URL指向项目文件夹控制器
     * var string
     */
    private static $urlController = null;

    /**
     * 当前URL指向项目文件夹控制器下面方法
     * var string
     */
    private static $urlMethod = null;

    /**
     * 当前URL的类别
     * @var
     */
    private static $url = ['urlProject', 'urlController', 'urlMethod'];

    /**
     * 分析URL，并转发
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月27日 10:26:24
     * @return bool
     */
    public static function parse()
    {
        self::setUrlModel();
        self::setOrdinaryUrl();
        self::setUserUrl();
        self::setUserIdUrl();
        self::setProductIdUrl();
        self::setActionUrl();
        self::setDefaultUrl();
        self::setResourceUrl();
        return self::load();
    }

    /**
     * 一般的URL模式，有错误就走默认控制器
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    public static function setOrdinaryUrl()
    {
        if (self::$urlModel !== __FUNCTION__) {
            return;
        }

        $url = parse_url($_SERVER['REQUEST_URI']);

        //先去掉多余的\和index.php
        $url = str_replace('index.php', '', $url['path']);
        $urlArray = explode(APP_SEGEMENT_SYMBOL, $url);
        $urlArray = array_filter($urlArray);
        self::setURL($urlArray);
    }

    /**
     * 用户名的URL模式
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    public static function setUserUrl()
    {
        if (self::$urlModel !== __FUNCTION__) {
            return;
        }

    }

    /**
     * 用户ID的URL模式
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 018年9月25日 10:58:12
     */
    public static function setUserIdUrl()
    {
        if (self::$urlModel !== __FUNCTION__) {
            return;
        }

    }

    /**
     * 商品ID的URL模式
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    public static function setProductIdUrl()
    {
        if (self::$urlModel !== __FUNCTION__) {
            return;
        }

    }

    /**
     * acation的URL模式
     * http://ai.com/?action=test&m=Home&do=setTest
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    public static function setActionUrl()
    {
        if (self::$urlModel !== __FUNCTION__) {
            return;
        }

        if (!isset($_GET['action'])) {
            return;
        }

        if (!isset($_GET['do'])) {
            return;
        }

        $urlArray = [];
        if (isset($_GET['m'])) {
            $urlArray['Project'] = $_GET['m'];
        } else {
            $config = Load::getConfig('init');
            $urlArray['Project'] = $config['index']['Project'];
        }

        $urlArray['Controller'] = $_GET['action'];
        $urlArray['Method'] = $_GET['do'];
        self::setURL($urlArray);
    }

    /**
     * 设置URL属于那种模式
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    private static function setUrlModel()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'action') !== false) {
            self::$urlModel = 'setActionUrl';
        }elseif  (strpos($_SERVER['REQUEST_URI'], 'CSS') !== false  || strpos($_SERVER['REQUEST_URI'], 'JS') !== false || strpos($_SERVER['REQUEST_URI'], 'IMG') !== false || strpos($_SERVER['REQUEST_URI'], 'FONT') !== false) {
            self::$urlModel = 'setResourceUrl';
        } else {
            self::$urlModel = 'setOrdinaryUrl';
        }

    }



    private static function setResourceUrl()
    {
        if (self::$urlModel !== null) {
            return;
        }

    }


    /**
     * 设置默认URL模式
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    private static function setDefaultUrl()
    {
        if (self::$urlModel !== null) {
            return;
        }

        $config = Load::getConfig('init');
        self::setURL($config['index']);
    }

    /**
     * 指向指定URL控制器
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    private static function load()
    {
        return Load::Controller(self::$urlProject, self::$urlController, self::$urlMethod);
    }


    /**
     * 设置URL
     * @param array $urlArray 携带的URL信息，在进入处理时候，会刷新传递进来的数组KEY，按0排起。
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年9月25日 10:58:12
     */
    private static function setURL($urlArray)
    {
        //走默认
        if ($urlArray == null) {
            self::$urlModel = null;
            return;
        }
        $urlArray = array_values($urlArray);
        foreach (self::$url as $key => $val) {
            if (isset($urlArray[$key])) {
                $_SERVER[$val] = self::$$val = filter_var($urlArray[$key], FILTER_SANITIZE_URL);
            }
        }
    }


}