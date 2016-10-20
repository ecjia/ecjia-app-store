<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺信息
 * @author luchongchong
 *
 */
class info_module implements ecjia_interface
{
    public function run(ecjia_api & $api)
    { 
		$ecjia = RC_Loader::load_app_class('api_admin', 'api');
		$ecjia->authadminSession();
//     	if($_SESSION['ru_id'] > 0) {
    	if($_SESSION['seller_id'] > 0) {
			$msi_dbview = RC_Loader::load_app_model('merchants_shop_information_viewmodel', 'seller');
			$msi_dbview->view = array(
					'seller_shopinfo' => array(
						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'ssi',
						'on'    => 'msi.shop_id = ssi.shop_id ',
					),
					'seller_category' => array(
							'type'  => Component_Model_View::TYPE_LEFT_JOIN,
							'alias' => 'sc',
							'on'    => 'ssi.cat_id = sc.cat_id',
					),
			);
			$region = RC_Loader::load_app_model('region_model', 'shipping');
			$where = array();
			$where['ssi.id'] = $_SESSION['seller_id'];
			$field ='ssi.*, msi.*, ssi.id as seller_id, ssi.shop_name as seller_name, sc.cat_name, ssi.shop_logo';
			$info = $msi_dbview->join(array('seller_category', 'seller_shopinfo'))
								 ->field($field)
								 ->where($where)
								 ->find();
			if(substr($info['shop_logo'], 0, 1) == '.') {
				$info['shop_logo'] = str_replace('../', '/', $info['shop_logo']);
			}
// 			$is_validated = (!empty($info['create_time']) && !empty($info['confirm_time'])) ? 1 : 0;
			 
			$seller_info = array(
	    	  		'id'					=> $info['seller_id'],
	    	  		'seller_name'			=> $info['seller_name'],
	    	  		'seller_logo'			=> RC_Upload::upload_url().'/'.$info['shop_logo'],
	    	  		'seller_category'		=> $info['cat_name'],
	    	  		'seller_telephone'		=> $info['kf_tel'],
	    	  		'seller_province'		=> $region->where(array('region_id'=>$info['province']))->get_field('region_name'),
	    	  		'seller_city'			=> $region->where(array('region_id'=>$info['city']))->get_field('region_name'),
	    	  		'seller_address'		=> $info['shop_address'],
	    	  		'seller_description'	=> $info['notice'],
					'validated_status'		=> $info['merchant_status'],
			);
			$result = $ecjia->admin_priv('merchant_setinfo');
			if (is_ecjia_error($result)) {
				$privilege = 1;
// 				Read & Write   3
// 				Read only      1
// 				No Access      0
			} else {
				$privilege = 3;
			}
    	 } else {
			$region = RC_Loader::load_app_model('region_model', 'shipping');
			$seller_info = array(
					'id'					=> 0,
	    	  		'seller_name'			=> ecjia::config('shop_name'),
	    	  		'seller_logo'			=> ecjia::config('shop_logo', ecjia::CONFIG_EXISTS) ? RC_Upload::upload_url().'/'.ecjia::config('shop_logo') : '',
	    	  		'seller_category'		=> null,
 	    	  		'seller_telephone'		=> ecjia::config('service_phone'),
 	    	  		'seller_province'		=> $region->where(array('region_id'=>ecjia::config('shop_province')))->get_field('region_name'),
 	    	  		'seller_city'			=> $region->where(array('region_id'=>ecjia::config('shop_city')))->get_field('region_name'),
	    	
	    	  		'seller_address'		=> ecjia::config('shop_address'),
	    	  		'seller_description'	=> strip_tags(ecjia::config('shop_notice'))
			);
			$result = $ecjia->admin_priv('shop_config');
			if (is_ecjia_error($result)) {
				$privilege = 1;
			} else {
				$privilege = 3;
			}
		}
		EM_Api::outPut($seller_info, null, $privilege);
// 		return $seller_info;
    }	
    
}