<?php

namespace application\Home\Model;

use system\MDB;

/**
 * 商品模型
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年10月9日 13:54:09
 * @version     0.1.0 版本号
 */
class goodsModel extends MDB
{

    /**
     * 首页随便看看列表
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年10月9日 13:54:09
     * @return bool array
     */
    public function getIndexList($limit = '', $offset = '')
    {
        if (is_numeric($limit)) {
            $limit = ' limit ' . $limit;
        }
        if (is_numeric($offset)) {
            $offset = ' offset ' . $offset;
        }
        $sql = 'SELECT * from wdt_goods WHERE is_on_sale = 1 and is_show = 1 and is_delete = 0 ORDER BY click_count ' . $limit . $offset;;
        $data = $this->get($sql);
        return $data;
    }


    /**
     * 推广商品列表
     * @author      zhy    find404@foxmail.com
     * @createTime 2018年10月11日 15:01:02
     * @return bool array
     */
    public function getGeneralizeList($limit = '', $offset = '')
    {
        if (is_numeric($limit)) {
            $limit = ' limit ' . $limit;
        }
        if (is_numeric($offset)) {
            $offset = ' offset ' . $offset;
        }
        $sql = 'SELECT * from wdt_goods WHERE is_on_sale = 1 and is_show = 1 and is_delete = 0 ORDER BY is_hot ' . $limit . $offset;;
        $data = $this->get($sql);
        return $data;
    }


    public function getList($data)
    {
        $sql = 'SELECT * from wdt_goods '.$data['where'].$data['orderby'].$data['by'].$data['limit'].$data['offset'];
        $data = $this->get($sql);
        return $data;
    }

}