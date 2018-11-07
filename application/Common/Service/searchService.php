<?php

namespace application\Common\Service;


/**
 * 搜索层
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年9月27日 17:34:40
 * @version     0.1.0 版本号
 */
class searchService
{

    /**
     * 映射数据库字段
     * @var
     */
    private static $fieldMap = [
        'keywords' => [
            'queryAction' => 'where',
            'joinAction' => 'like%',
            'field' => 'goods_sn',
            'type' => 'string',
        ],
        'brand' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'field' => 'brand_id',
            'type' => 'int',
        ],
        'price_min' => [
            'queryAction' => 'where',
            'joinAction' => '>=',
            'field' => 'market_price',
            'type' => 'int',
        ],
        'price_max' => [
            'queryAction' => 'where',
            'joinAction' => '<=',
            'field' => 'market_price',
            'type' => 'int',
        ],
        'page' => [
            'queryAction' => 'offset',
            'joinAction' => '',
            'default' => 0,
            'type' => 'int',
        ],
        'pageSize' => [
            'queryAction' => 'limit',
            'joinAction' => '',
            'default' => 20,
            'type' => 'int',
        ],
        'sort' => [
            'queryAction' => 'order',
            'joinAction' => '',
            'default' => 'goods_id',
            'type' => 'string',
        ],
        'order' => [
            'queryAction' => 'by',
            'joinAction' => '',
            'default' => 'ASC',
            'type' => 'string',
        ],
        //自营
        'is_self' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'field' => [],
            'type' => 'string',
        ],
        //仅显示有货
        'have' => [
            'queryAction' => 'where',
            'joinAction' => '>',
            'field' => 'goods_number',
            'type' => 'string',
        ],
        //包邮
        'is_ship' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'field' => 'is_shipping',
            'type' => 'string',
        ],


        //默认条件
        'is_on_sale' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'default' => 1,
            'field' => 'is_on_sale',
            'type' => 'int',
        ],
        //默认条件
        'is_show' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'default' => 1,
            'field' => 'is_show',
            'type' => 'int',
        ],
        //默认条件
        'is_delete' => [
            'queryAction' => 'where',
            'joinAction' => '=',
            'default' => 0,
            'field' => 'is_shipping',
            'type' => 'int',
        ],

    ];

    /**
     * 通过搜索条件剥离为SQL语句
     * @param array $data 用户搜索数组
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年10月11日 17:33:07
     * @return array
     */
    public static function index($data)
    {
        if (!$data) {
            return;
        }
        //需要设置默认值得数据
        $defuat = ['page', 'pageSize', 'sort', 'order', 'is_on_sale', 'is_show', 'is_delete'];

        $params = \system\Functions::processSeachGoodsListParam($data, self::$fieldMap, $defuat);
        \system\Functions::setWhere($params);
        \system\Functions::setLimit();
        \system\Functions::setOffset();
        \system\Functions::setOrder();
        \system\Functions::setBy();

        $sqlParams = \system\Functions::getFuncSqlData();
        $List = \system\Load::Model('Common\goodsModel')->getList($sqlParams);
        //$processList = self::processGoodsListToRighticFormat($List);
        //return $processList;
        return $List;
    }

    /**
     * 通过前台搜索条件，带入搜索引擎查找数据
     * @param $data array 前台传递参数
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月23日 13:58:15
     * @return array
     */
    public static function getSearchEnginesListByParams($data)
    {
        if (!isset($data['keywords']) || empty($data['keywords'])) {
            return;
        }
        $ids = [];
        $coreseekList = \system\Load::coreseek('getResultByVagueSearch', $data['keywords']);
        if (isset($coreseekList['matches'])) {
            if ($coreseekList['matches'] != null) {
                $ids = array_keys($coreseekList['matches']);
            }
        }

        //$list = \system\Load::Model('Common\productTemplateModel')->getListById($ids);
        //增加高亮
        //$data['list'] = \system\Load::coreseek('setResultBuildExcerpts',$list, $data['keywords']);
        //$data['page'] = 0;
        //$data['pageCount'] = 1000;
        //总发现条数
        //$data['total_found'] = $coreseekList['total_found'];
        return $ids;
    }


}