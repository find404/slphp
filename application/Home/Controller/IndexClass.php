<?php

namespace application\Home\Controller;


/**
 * 首页
 * @author      zhy    find404@foxmail.com
 * @createTime   2018年9月25日 10:36:58
 * @version     0.1.0 版本号
 */
class IndexClass
{

    /**
     * 首页
     * @throws
     * @author     zhy    find404@foxmail.com
     * @createTime 2018年10月8日 18:04:57
     */
    public function index()
    {

		/*
		if(!isset($_GET['keywords'])){
            \system\Functions::outputJson(['code'=>202],'msg'=>'请传入partnumber参数!');
        }

        if(!isset($_GET['type'])){
            \system\Functions::outputJson(['code'=>202],'msg'=>'请传入type参数!');
        }
        //要查询的型号
        $partnumber = filter_var($_GET['keywords'], FILTER_SANITIZE_STRING);
		if($partnumber == ''){
            \system\Functions::outputJson(['code'=>202],'msg'=>'请传入实际keywords参数错误!');
        }
        $searchGoodsList = \system\Load::Service('Common\searchService')->getRemoteSupplierData($_GET);
		\system\Functions::outputJson(['code'=>200],'msg'=>'获取成功！','list'=>$searchGoodsList);
		*/
		
		

        //首页商品
        \system\Load::$_viewData['List'] = ['test data'];
        \system\Load::View('index');
    }






}