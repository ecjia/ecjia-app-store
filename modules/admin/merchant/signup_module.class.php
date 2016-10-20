<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 申请商家入驻
 * @author will.chen
 *
 */
class signup_module implements ecjia_interface
{
    public function run(ecjia_api & $api)
    {
		$responsible_person	= isset($_POST['responsible_person']) ? trim($_POST['responsible_person']) : '';
		$email				= isset($_POST['email']) ? trim($_POST['email']) : '';
		$company_name		= isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
		$mobile				= isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$seller_name		= isset($_POST['seller_name']) ? trim($_POST['seller_name']) : '';
		
		if (empty($responsible_person) || empty($email) || empty($mobile) || empty($seller_name)) {
			return new ecjia_error(101, '错误的参数提交');
		}
		
		$info = RC_Model::model('merchant/merchants_shop_information_model')->find(array('contact_mobile' => $mobile));
		if (!empty($info)) {
			return new ecjia_error('already_signup', '您已申请请勿重复申请！');
		}
		
		$merchant_shop_data = array(
				'responsible_person'	=> $responsible_person,
				'company_name'			=> $company_name,
				'contact_mobile'		=> $mobile,
				'steps_audit'	=> 1,
				'create_time'	=> RC_Time::gmtime(),
		);
		
		$insert_id = RC_Model::model('merchant/merchants_shop_information_model')->insert($merchant_shop_data);
		
		RC_Model::model('seller/seller_shopinfo_model')->insert(array('shop_id' => $insert_id));
		
		return array();
    }	
    
}