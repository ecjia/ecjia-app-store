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
		$mobile				= $this->requestData('mobile', '');
		$seller_name		= $this->requestData('seller_name', '');
		$seller_category	= $this->requestData('seller_category', '');
		$validate_type		= $this->requestData('validate_type');
		$province			= $this->requestData('province');
		$city				= $this->requestData('city');
		$district			= $this->requestData('district');
		$address			= $this->requestData('address');
		$longitude			= $this->requestData('location.longitude');
		$latitude			= $this->requestData('location.latitude');
		$validate_code		= $this->requestData('validate_code');


		if (empty($responsible_person) || empty($email) || empty($mobile) || empty($seller_name) || empty($seller_category)
		     || empty($validate_type) || empty($province) || empty($city)|| empty($district) || empty($address) || empty($longitude) || empty($latitude)) {
			return new ecjia_error( 'invalid_parameter', RC_Lang::get ('system::system.invalid_parameter' ));
		}

		/* 判断校验码*/
		if ($_SESSION['merchant_validate_code'] != $validate_code) {
			return new ecjia_error('validate_code_error', '校验码错误！');
		} elseif ($_SESSION['merchant_validate_expiry'] < RC_Time::gmtime()) {
			return new ecjia_error('validate_code_time_out', '校验码已过期！');
		}

		$info_store_preaudit	= RC_DB::table('store_preaudit')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
		$info_store_franchisee	= RC_DB::table('store_franchisee')->where(RC_DB::raw('contact_mobile'), $mobile)->first();
		$info_staff_user		= RC_DB::table('staff_user')->where('mobile', $mobile)->first();
		if (!empty($info_store_preaudit) || !empty($info_store_franchisee) || !empty($info_staff_user)) {
			return new ecjia_error('already_signup', '您已申请请勿重复申请！');
		}

		$merchant_shop_data = array(
				'responsible_person'	=> $responsible_person,
				'merchants_name'		=> $seller_name,
				'contact_mobile'		=> $mobile,
				'email'					=> $email,
				'check_status'			=> 1,
				'apply_time'			=> RC_Time::gmtime(),
				'store_id'				=> 0,
				'cat_id'				=> $seller_category,
				'validate_type'			=> $validate_type,
				'province'				=> $province,
				'city'					=> $city,
				'district'				=> $district,
				'address'				=> $address,
				'longitude'				=> $longitude,
				'latitude'				=> $latitude,
		);

		$insert_id = RC_DB::table('store_preaudit')->insertGetId($merchant_shop_data);

		unset($_SESSION['merchant_validate_code']);
		unset($_SESSION['merchant_validate_expiry']);
		return array();
    }

}
