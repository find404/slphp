<?php

namespace system;

/**
 * 常用方法
 * @author      zhy    find404@foxmail.com
 * @createTime   2018年9月25日 10:36:58
 * @version     1.0 版本号
 */
class Functions
{

    /**
     * 当前一级方法
     * @var
     */
    private static $majorFunction = null;

    /**
     * 当前二级方法
     * @var
     */
    private static $minorFunction = null;

    /**
     * 当前方法参数
     * @var
     */
    private static $functionArges = null;

    /**
     * 当前方法参数
     * @var
     */
    private static $curlInit = null;

    /**
     * 当前Sql方法的结果集
     * @var
     */
    private static $funcSqlData = [];

    /**
     * 当前方法参数
     * @var
     */
    private static $functionMapp = [
        'setWhere' => 'funSql',
        'setOrder' => 'funSql',
        'setBy' => 'funSql',
        'setLimit' => 'funSql',
        'setOffset' => 'funSql',
        'where1' => 'funSql',
        'processSeachGoodsListParam' => 'funArray',
        'appendArrayKey' => 'funArray',
        'getCurl' => 'funCurl',
        'getCurls' => 'funCurl',
        'outputJson' => 'funEcho',
    ];

    /**
     * SQL方法系列函数
     * @param 按需调用，数字键值数组
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:39
     * @return bool
     */
    public static function funSql()
    {
        $setWhere = function () {
            if (!isset(self::$functionArges[0])) {
                return;
            }
            $where = '';
            foreach (self::$functionArges[0] as $key => $val) {
                if (is_array($val)) {
                    if ($val['queryAction'] == 'where') {
                        switch ($val['joinAction']) {
                            case '=':
                            case '>=':
                            case '<=':
                            case '>':
                            case '<':
                                $where .= empty($where) ? ' where' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' ' . $val['value'] : ' and' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' ' . $val['value'];
                                break;
                            case 'like':
                                $where .= empty($where) ? ' where ' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' ' . $val['value'] : 'and' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' ' . $val['value'];
                                break;
                            case 'inInt':
                                if(is_array($val['value'])){
                                    $val['value'] = implode(",",$val['value']);
                                }
                                $where .= empty($where) ? ' where' . ' ' . $val['field'] . ' in (' . $val['value'] . ')' : ' and' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' (' . $val['value'] . ')';
                                break;
                            case 'inString':
                                if(is_array($val['value'])){
                                    $val['value'] = implode("','",$val['value']);
                                }
                                $where .= empty($where) ? ' where' . ' ' . $val['field'] . ' in (' . $val['value'] . ')' : ' and' . ' ' . $val['field'] . ' ' . $val['joinAction'] . ' (' . $val['value'] . ')';
                                break;
                            case 'like%':
                                $where .= empty($where) ? ' where ' . ' ' . $val['field'] . ' like' . ' \'' . $val['value'] . '%\'' : ' and ' . ' ' . $val['field'] . ' like' . ' \'' . $val['value'] . '%\'';
                                break;
                            case '%like':
                                $where .= empty($where) ? ' where ' . ' ' . $val['field'] . ' like' . ' %\'' . $val['value'] . '\'' : ' and ' . ' ' . $val['field'] . ' like' . ' %\'' . $val['value'] . '\'';
                                break;
                            case '%like%':
                                $where .= empty($where) ? ' where ' . ' ' . $val['field'] . ' like' . ' %\'' . $val['value'] . '%\'' : ' and ' . ' ' . $val['field'] . ' like' . ' %\'' . $val['value'] . '%\'';
                                break;
                            default:
                        }
                    }
                }
            }
            self::$funcSqlData['where'] = $where;
        };

        $setOrder = function () {
            if (self::$functionArges[0] == null) {
                return;
            }
            foreach (self::$functionArges[0] as $key => $val) {
                if (is_array($val)) {
                    if ($val['queryAction'] == 'order') {
                        self::$funcSqlData['orderbyValue'] = $val['value'];
                        self::$funcSqlData['orderby'] = ' order by ' . $val['value'];
                        break;
                    }
                }
            }
        };

        $setBy = function () {
            if (self::$functionArges[0] == null) {
                return;
            }
            foreach (self::$functionArges[0] as $key => $val) {
                if (is_array($val)) {
                    if ($val['queryAction'] == 'by') {
                        self::$funcSqlData['by'] = ' ' . $val['value'];
                        break;
                    }
                }
            }
        };

        $setLimit = function () {
            if (self::$functionArges[0] == null) {
                return;
            }
            foreach (self::$functionArges[0] as $key => $val) {
                if (is_array($val)) {
                    if ($val['queryAction'] == 'limit') {
                        self::$funcSqlData['limit'] = isset($val['value']) ? ' limit ' . $val['value'] : ' limit ' . $val['default'];
                        self::$funcSqlData['limitValue'] = isset($val['value']) ? $val['value'] : $val['default'];
                        break;
                    }
                }
            }
            if (!isset(self::$funcSqlData['limit'])) {
                self::$funcSqlData['limit'] = ' limit 20';
            }
        };

        $setOffset = function () {
            if (self::$functionArges[0] == null) {
                return;
            }
            if (!isset(self::$funcSqlData['limitValue'])) {
                return;
            }
            foreach (self::$functionArges[0] as $key => $val) {
                if (is_array($val)) {
                    if ($val['queryAction'] == 'offset') {
                        $offset = $val['value'] * self::$funcSqlData['limitValue'];
                        self::$funcSqlData['offsetValue'] = $offset;
                        self::$funcSqlData['offset'] = ' offset ' . $offset;
                        break;
                    }
                }
            }
        };

        return ${self::$minorFunction}();
    }


    /**
     * 获取当前设置类的参数
     * @param $key 当前参数的键值
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:39
     * @return bool
     */
    public static function getFuncSqlData()
    {
        return self::$funcSqlData;
    }

    public static function emptyFuncSqlData()
    {
        self::$funcSqlData = [];
    }

    /**
     * SQL方法系列函数
     * @param 按需调用，数字键值数组
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:39
     * @return bool
     */
    public static function funArray()
    {
        //处理搜索页面参数，类型处理，加上默认值
        $processSeachGoodsListParam = function () {
            if (!isset(self::$functionArges[0]) || !isset(self::$functionArges[1])) {
                return;
            }
            $sqlList = [];
            //用户输入字段
            foreach (self::$functionArges[0] as $key => $val) {
                if ($val) {
                    if (isset(self::$functionArges[1][$key])) {
                        switch (self::$functionArges[1][$key]['type']) {
                            case 'string':
                                $val = filter_var($val, FILTER_SANITIZE_STRING);
                                break;
                            case 'int':
                                $val = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
                                break;
                            case 'float':
                                $val = filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT);
                            case 'array':
                                //$val = ;
                                break;
                            default:
                        }
                        //是否需要默认值
                        if (isset(self::$functionArges[1][$key]['default']) && !$val) {
                            $val = self::$functionArges[1][$key]['default'];
                        }
                        $sqlList[$key] = self::$functionArges[1][$key] + ['value' => $val];
                    }
                }
            }

            //追加默认字段
            if (isset(self::$functionArges[2])) {
                if (is_array(self::$functionArges[2])) {
                    foreach (self::$functionArges[2] as $val) {
                        if (!isset(self::$functionArges[0][$val])) {
                            if (!isset(self::$functionArges[1])) {
                                break;
                            }
                            if (!isset(self::$functionArges[1][$val])) {
                                break;
                            }
                            if (!isset(self::$functionArges[1][$val]['default'])) {
                                break;
                            }
                            $sqlList[$val] = self::$functionArges[1][$val] + ['value' => self::$functionArges[1][$val]['default']];
                        }
                    }
                }
            }
            return $sqlList;
        };

        $appendArrayKey = function (){
            if (!isset(self::$functionArges[0]) || !isset(self::$functionArges[1])) {
                return;
            }
            $variableKeys = explode('=',self::$functionArges[1]);

            foreach (self::$functionArges[0] as $key=>$val){
                $varKey = '';
                foreach ($variableKeys as $va){
                    if(isset($val[$va])){
                        $varKey .= empty($varKey) ? $val[$va] : '_'.$val[$va];
                    }
                }
                unset(self::$functionArges[0][$key]);
                self::$functionArges[0][$varKey] = $val;
            }
            return self::$functionArges[0];
        };

        return ${self::$minorFunction}();
    }


    /**
     * CURL方法系列函数
     * @param 按需调用，数字键值数组
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:39
     * @return bool
     */
    public static function funCurl()
    {
        $getCurl = function () {
            if (!isset(self::$functionArges[0])) {
                return;
            }
            //初始化
            if(self::$curlInit === null){
                self::$curlInit = curl_init();
            }
            $url = self::$functionArges[0];
            if(!filter_var($url, FILTER_VALIDATE_URL)) {
                \system\LocalLog::write('当前异步获取数据，该链接错误'.$url);
                return false;
            }
            curl_setopt(self::$curlInit, CURLOPT_HTTPGET, 1);
            curl_setopt(self::$curlInit, CURLOPT_URL, $url);
            curl_setopt(self::$curlInit, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(self::$curlInit, CURLOPT_CONNECTTIMEOUT, 1);
            //curl_setopt(self::$curlInit, CURLOPT_TIMEOUT, 20);
            curl_setopt(self::$curlInit, CURLOPT_REFERER, 'baidu.com');
            curl_setopt(self::$curlInit, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json; charset=utf-8',
                ]
            );
            curl_setopt(self::$curlInit, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.92 Safari/537.36');
            $returnData = curl_exec(self::$curlInit);
            //if ($returnData === false) {
                //$returnData = curl_error(self::$curlInit);
            //}
            curl_close(self::$curlInit);
            return $returnData;
        };

        $getCurls = function () {
            if (!isset(self::$functionArges[0])) {
                return;
            }
            $node_count = count(self::$functionArges[0]);
            $curl_arr = [];
            $master = curl_multi_init();
            $curlMultiResults = [];
            for ($i = 0; $i < $node_count; $i++) {
                $url = self::$functionArges[0][$i];
                if(filter_var($url, FILTER_VALIDATE_URL))
                {
                    $curl_arr[$i] = curl_init($url);
                    curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
                    curl_multi_add_handle($master, $curl_arr[$i]);
                }else{
                    \system\LocalLog::write('当前异步获取数据，该链接错误'.$url);
                }
            }
            do {
                curl_multi_exec($master, $running);
            } while ($running > 0);

            for ($i = 0; $i < $node_count; $i++) {
                if(isset($curl_arr[$i])){
                    $curlMultiResults[] = curl_multi_getcontent($curl_arr[$i]);
                }
            }
            return $curlMultiResults;
        };


        return ${self::$minorFunction}();
    }


    /**
     * CURL方法系列函数
     * @param 按需调用，数字键值数组
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:39
     * @return bool
     */
    public static function funEcho()
    {
        $outputJson = function () {
            if (!isset(self::$functionArges[0])) {
                return;
            }
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(self::$functionArges[0]));
            //exit(json_encode(self::$functionArges[0], JSON_HEX_TAG));
        };


        return ${self::$minorFunction}();

    }


    /**
     * 转发器
     * @param
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:25:10
     * @return bool
     */
    public static function __callStatic($func, $arguments)
    {
        if (!isset(self::$functionMapp[$func])) {
            return;
        }
        self::$majorFunction = self::$functionMapp[$func];
        self::$minorFunction = $func;
        if ($arguments != null) {
            self::$functionArges = $arguments;
        }
        $fun = self::$functionMapp[$func];
        return self::$fun();
    }


}