<?php

if (!function_exists('getConnection')) {
    function getConnection()
    {
        static $connections = [];
        if ($connections == null) {
            $searchEnginesConfig = \system\Load::getConfig('SearchEngines');
            include APP_PATH.'/system/SearchEngines/coreseek/sphinxapi.php';
            $connections['obj'] = new SphinxClient();

            if (!isset($searchEnginesConfig['coreseek'])) {
                exit('请配置好coreseek');
            }
            if (!isset($searchEnginesConfig['coreseek'][0])) {
                exit('请配置好coreseek');
            }
            if (!isset($searchEnginesConfig['coreseek'][0]['host'])) {
                exit('请配置好coreseek的host');
            }

            if (!isset($searchEnginesConfig['coreseek'][0]['port'])) {
                exit('请配置好coreseek的port');
            }

            if (!isset($searchEnginesConfig['coreseek'][0]['defaultIndex'])) {
                exit('请配置好coreseek的defaultIndex');
            }

            $connections['obj']->SetServer($searchEnginesConfig['coreseek'][0]['host'], $searchEnginesConfig['coreseek'][0]['port']);
            $connections['defaultIndex'] = $searchEnginesConfig['coreseek'][0]['defaultIndex'];
            $connections['obj']->SetLimits(0, 20);
            //$connections['obj']->setMatchMode(SPH_MATCH_FULLSCAN);
        }
        return $connections['obj'];
    }

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
if (!function_exists('getResultByVagueSearch')) {
    function getResultByVagueSearch($keyword, $index = 'pgsqlSearchName')
    {
        if ($index == null) {
            $searchEnginesConfig = \system\Load::getConfig('SearchEngines');
            $index = $searchEnginesConfig['coreseek'][0]['defaultIndex'];
        }
        $result = getConnection()->Query('*' . $keyword . '*', $index);
        return $result;
    }
}

if (!function_exists('setResultBuildExcerpts')) {
    function setResultBuildExcerpts($data, $keyword, $index = 'name')
    {
        if ($data == null) {
            return;
        }
        foreach ($data as $key => $val) {
            $startIndex = stripos($val[$index], $keyword);
            $data[$key][$index . 'Show'] = substr_replace($val[$index], '<span style="color: red">', $startIndex, 0);
            $endIndex = stripos($val[$index], $keyword) + mb_strlen('<span style="color: red">') + mb_strlen($keyword);
            $data[$key][$index . 'Show'] = substr_replace($data[$key][$index . 'Show'], '</span>', $endIndex, 0);
        }
        return $data;
    }
}


?>