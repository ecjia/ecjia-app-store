<?php
/**
* 添加管理员记录日志操作对象
*/
function assign_adminlog_content() {
	ecjia_admin_log::instance()->add_object('merchants_commission','佣金结算');
	ecjia_admin_log::instance()->add_object('merchants_commission_stats','佣金结算状态');
	
	ecjia_admin_log::instance()->add_object('merchants_step', '申请流程');
	ecjia_admin_log::instance()->add_object('merchants_step_title', '申请流程信息');
	ecjia_admin_log::instance()->add_object('merchants_step_custom', '自定义字段');
	
	ecjia_admin_log::instance()->add_object('seller', '入驻商');
	ecjia_admin_log::instance()->add_object('merchants_brand', '商家品牌');
	ecjia_admin_log::instance()->add_object('store_category', '店铺分类');
	
	ecjia_admin_log::instance()->add_object('config', '配置');
	
	ecjia_admin_log::instance()->add_object('store_percent', '佣金比例');
	
	ecjia_admin_log::instance()->add_object('seller_mobileconfig', '店铺街配置');
}

//end