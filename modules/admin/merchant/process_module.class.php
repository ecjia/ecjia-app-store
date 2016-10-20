<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 申请商家入驻查询进度
 * @author will.chen
 *
 */
class process_module implements ecjia_interface
{
    public function run(ecjia_api & $api)
    {
    	$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    	if (empty($mobile)) {
    		return new ecjia_error(101, '错误的参数提交');
    	}
    	
    	$info = RC_Model::model('merchant/merchants_shop_information_model')->find(array('contact_mobile' => $mobile));
    	
    	if (empty($info)) {
    		return new ecjia_error('merchant_errors', '您还未申请商家入驻！');
    	}
    	
    	return array('process' => $info['merchant_status'], 'message' => $info['merchants_message']);
    }	
    
}