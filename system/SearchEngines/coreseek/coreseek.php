<?php




/**
 * coreseek搜索引擎
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年10月22日 17:29:12
 * @version     0.1.0 版本号
 */
class coreseek
{

    /**
     * 静态类
     * @var
     */
    private static $connections = null;

    /**
     * 默认索引
     * @var
     */
    private static $defaultIndex = null;


    /**
    * 获取连接层信息
    * @author     zhy	find404@foxmail.com
    * @createTime 2018年10月22日 17:54:35
    */
    private static function getConnection()
    {
        $searchEnginesConfig = Load::getConfig('SearchEngines');
        if (self::$connections == null) {
            require_once ('F:/icshop/aiphp/system/SearchEngines/coreseek/SphinxClientFunction.php');
            require ('F:/icshop/aiphp/system/SearchEngines/coreseek/SphinxClient.php');
            self::$connections = new SphinxClient();
        }

        if (!isset($searchEnginesConfig['coreseek'])) {
            exit('请配置好coreseek');
        }
        if (!isset($searchEnginesConfig['coreseek'][0])) {
            exit('请配置好coreseek');
        }
        if (!isset($searchEnginesConfig['coreseek'][0]['host'])) {
            exit('请配置好coreseek的host');
        }

        if (!isset($searchEnginesConfig['coreseek'][0]['host'])) {
            exit('请配置好coreseek的port');
        }

        if (!isset($searchEnginesConfig['coreseek'][0]['host'])) {
            exit('请配置好coreseek的defaultIndex');
        }

        self::$connections->SetServer($searchEnginesConfig['coreseek'][0]['host'], $searchEnginesConfig['coreseek'][0]['port']);
        self::$defaultIndex = $searchEnginesConfig['coreseek'][0]['defaultIndex'];
        self::$connections->SetLimits(0, 20);
    }

    /**
     * 模糊搜索
     * @param string $keyword 关键词
     * @param string $index 数据源
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月22日 17:50:46
     * @return bool
     */
    public static function getResultByVagueSearch($keyword, $index = '')
    {
        self::getConnection();
        if ($index == null) {
            $index = self::$defaultIndex;
        }
        $result = self::$connections->Query('*' . $keyword . '*', $index);
        return $result;
    }


}

?>