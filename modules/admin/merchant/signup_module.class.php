<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 申请商家入驻
 * @author will.chen
 *
 */
class signup_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
		$responsible_person = $this->requestData('responsible_person', '');
		$email 				= $this->requestData('email', '');
		$company_name		= $this->requestData('company_name', '');
		$mobile				= $this->requestData('mobile', '');
		$seller_name		= $this->requestData('seller_name', '');
		
		if (empty($responsible_person) || empty($email) || empty($mobile) || empty($seller_name)) {
			return new ecjia_error(101, '错误的参数提交');
		}
		
		//$info = RC_Model::model('merchant/merchants_shop_information_model')->find(array('contact_mobile' => $mobile));
		$info_store_preaudit = RC_DB::table('store_preaudit')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
		$info_store_franchisee = RC_DB::table('store_franchisee')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
		
		if (!empty($info_store_preaudit) || !empty($info_store_franchisee)) {
			return new ecjia_error('already_signup', '您已申请请勿重复申请！');
		}
		
		$merchant_shop_data = array(
				'responsible_person'	=> $responsible_person,
				'company_name'			=> $company_name,
				'merchants_name'		=> $seller_name,
				'contact_mobile'		=> $mobile,
				'email'					=> $email,
				'check_status'			=> 1,
				'apply_time'			=> RC_Time::gmtime(),
				'store_id'				=> 0,
		);
		
		//$insert_id = RC_Model::model('merchant/merchants_shop_information_model')->insert($merchant_shop_data);
		//RC_Model::model('seller/seller_shopinfo_model')->insert(array('shop_id' => $insert_id));
		$insert_id = RC_DB::table('store_preaudit')->insertGetId($merchant_shop_data);
		return array();
    }	
    
}