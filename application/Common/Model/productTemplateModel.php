<?php

namespace application\Common\Model;


/**
 * 商品模型
 * @author      zhy    find404@foxmail.com
 * @createTime 2018年10月17日 18:25:24
 * @version     0.1.0 版本号
 */
class productTemplateModel extends MDB
{

    /**
     * 数据库数据
     * @var int
     */
    const SELF_GOODS = 0;

    /**
     * 数据库数据ID（永久缓存）
     * @var int
     */
    const IDS_GOODS = 1;

    /**
     * 远程数据
     * @var int
     */
    const REMOTE_GOODS = 2;

    public function getList($data)
    {
        $sql = 'SELECT * from product_template limit 1 ';
        $data = $this->get($sql);
        return $data;
    }

    public function getListById($ids)
    {
        if ($ids == null) {
            return;
        }
        //$sql = 'SELECT id,name,list_price,score from "public"."product_template" where id in (' . implode(",", $ids) . ') and active = true and sale_ok=true and normal_state = 2';
        $sql = 'SELECT id,name,list_price,score from "public"."product_template" where id in (' . implode(",", $ids) . ') and active = true and sale_ok = true limit 20';
        $data = $this->get($sql);
        return $data;
    }

    /**
     * 设置商品ID缓存
     * @param    $keywords   string 前台传递的搜索关键词
     * @param    $data   array   需要缓存的数据
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function setGoodsIdsCache($keywords, $supplierAbb, $data)
    {
        $key = MD5($supplierAbb . '_' . self::IDS_GOODS . '_' . $keywords);
        return \system\redis::set($key, $data);
    }

    /**
     * 获取商品ID缓存
     * @param    $keywords   string 前台传递的搜索关键词
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function getGoodsIdsCache($keywords, $supplierAbb)
    {
        $key = MD5($supplierAbb . '_' . self::IDS_GOODS . '_' . $keywords);
        return \system\redis::get($key);
    }

    /**
     * 设置ichub商品数据缓存（2小时有效）
     * @param    $keywords   string 前台传递的搜索关键词
     * @param    $supplierAbb   string 前台传递商户标识
     * @param    $data   array   需要缓存的数据
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function setSelfGoodsCache($keywords, $supplierAbb, $data)
    {
        $key = MD5($supplierAbb . '_' . self::SELF_GOODS . '_' . $keywords);
        return \system\redis::setex($key, $data, 2);
    }

    /**
     * 获取ichub商品数据缓存（2小时有效）
     * @param    $keywords   string 前台传递的搜索关键字
     * @param    $supplierAbb   string 前台传递商户标识
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function getSelfGoodsCache($keywords, $supplierAbb)
    {
        $key = MD5($supplierAbb . '_' . self::SELF_GOODS . '_' . $keywords);
        return \system\redis::get($key);
    }

    /**
     * 设置远程商品缓存（2小时有效）
     * @param    $keywords   string 前台传递的搜索关键词
     * @param    $supplierAbb   string 前台传递商户标识
     * @param    $data   array   需要缓存的数据
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function setRemoteGoodsCache($keywords, $supplierAbb, $data)
    {
        $key = MD5($supplierAbb . '_' . self::REMOTE_GOODS . '_' . $keywords);
        return \system\redis::setex($key, $data, 2);
    }

    /**
     * 设置远程商品缓存（2小时有效）
     * @param    $keywords   string 前台传递的搜索关键词
     * @param    $supplierAbb   string 前台传递商户标识
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年11月1日 13:54:36
     * @return bool
     */
    public function getRemoteGoodsCache($keywords, $supplierAbb)
    {
        $key = MD5($supplierAbb . '_' . self::REMOTE_GOODS . '_' . $keywords);
        return \system\redis::get($key);
    }

}