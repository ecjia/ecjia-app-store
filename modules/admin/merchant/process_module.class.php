<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 申请商家入驻查询进度
 * @author will.chen
 *
 */
class process_module  extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
    	$mobile = $this->requestData('mobile', '13468678174');
    	if (empty($mobile)) {
    		return new ecjia_error(101, '错误的参数提交');
    	}
    	
    	//$info = RC_Model::model('merchant/merchants_shop_information_model')->find(array('contact_mobile' => $mobile));
    	$info_store_preaudit = RC_DB::table('store_preaudit')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
    	$info_store_franchisee = RC_DB::table('store_franchisee')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
    	
    	if (empty($info_store_preaudit) && empty($info_store_franchisee)) {
    		return new ecjia_error('merchant_errors', '您还未申请商家入驻！');
    	}
    	//return array('process' => $info['merchant_status'], 'message' => $info['merchants_message']);
    	if (!empty($info_store_preaudit)) {
    		return array('process' => $info_store_preaudit['check_status']);
    	}
    	if (!empty($info_store_franchisee)) {
    		return array('process' => '2');
    	}
    }	
    
}