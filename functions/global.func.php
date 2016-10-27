<?php
/**
* 添加管理员记录日志操作对象
*/
function assign_adminlog_content() {
	ecjia_admin_log::instance()->add_object('store_commission','佣金结算');
	ecjia_admin_log::instance()->add_object('store_commission_status','佣金结算状态');

	ecjia_admin_log::instance()->add_object('merchants_step', '申请流程');
	ecjia_admin_log::instance()->add_object('merchants_step_title', '申请流程信息');
	ecjia_admin_log::instance()->add_object('merchants_step_custom', '自定义字段');

	ecjia_admin_log::instance()->add_object('seller', '入驻商');
	ecjia_admin_log::instance()->add_object('merchants_brand', '商家品牌');
	ecjia_admin_log::instance()->add_object('store_category', '店铺分类');
	ecjia_admin_log::instance()->add_object('merchant_notice', '商家公告');

	ecjia_admin_log::instance()->add_object('config', '配置');
	ecjia_admin_log::instance()->add_object('store_percent', '佣金比例');
	ecjia_admin_log::instance()->add_object('store_mobileconfig', '店铺街配置');
}

/**
* 设置页面菜单
*/
function set_store_menu($store_id, $key){

    $keys = array('preview','store_set','commission_set','commission','view_staff','view_log');
    if(!in_array($key,$keys)){
        $key = 'preview';
    }
    $arr = array(
        array(
            'menu'  => '基本信息',
            'name'  => 'preview',
            'url'   => RC_Uri::url('store/admin/preview', array('store_id' => $store_id))
        ),
        array(
            'menu'  => '店铺设置',
            'name'  => 'store_set',
            'url'   => RC_Uri::url('store/admin/store_set', array('store_id' => $store_id))
        ),
        array(
            'menu'  => '设置佣金',
            'name'  => 'commission_set',
            'url'   => RC_Uri::url('store/admin_commission/edit', array('store_id' => $store_id))
        ),
        array(
            'menu'  => '结算账单',
            'name'  => 'commission',
            'url'   => RC_Uri::url('commission/admin/init', array('store_id' => $store_id))
        ),
        array(
            'menu'  => '查看员工',
            'name'  => 'view_staff',
            'url'   => RC_Uri::url('store/admin/view_staff', array('store_id' => $store_id))
        ),
        array(
            'menu'  => '查看日志',
            'name'  => 'view_log',
            'url'   => RC_Uri::url('store/admin/view_log', array('store_id' => $store_id))
        ),
    );
    foreach($arr as $k => $val){
        if($key == $val['name']){
            $arr[$k]['active'] = 1;
            $arr[$k]['url'] = "#tab".($k+1);
        }
    }
    return $arr;
}

//end
