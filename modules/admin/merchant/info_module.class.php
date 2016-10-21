<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺信息
 * @author luchongchong
 *
 */
class info_module extends api_admin implements api_interface {
    
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
		$this->authadminSession();
    	if($_SESSION['store_id'] > 0) {
// 			$msi_dbview = RC_Model::model('store/store_franchisee_model');
// 			$msi_dbview->view = array(
// 					'seller_shopinfo' => array(
// 						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
// 						'alias' => 'ssi',
// 						'on'    => 'msi.shop_id = ssi.shop_id ',
// 					),
// 					'seller_category' => array(
// 							'type'  => Component_Model_View::TYPE_LEFT_JOIN,
// 							'alias' => 'sc',
// 							'on'    => 'ssi.cat_id = sc.cat_id',
// 					),
// 			);
			$region = RC_Model::model('shipping/region_model');
			$where = array();
			/* $where['ssi.id'] = $_SESSION['store_id'];
			$field ='ssi.*, msi.*, ssi.id as seller_id, ssi.shop_name as seller_name, sc.cat_name, ssi.shop_logo';
			$info = $msi_dbview->join(array('seller_category', 'seller_shopinfo'))
								 ->field($field)
								 ->where($where)
								 ->find();
			if(substr($info['shop_logo'], 0, 1) == '.') {
				$info['shop_logo'] = str_replace('../', '/', $info['shop_logo']);
			} */
// 			$is_validated = (!empty($info['create_time']) && !empty($info['confirm_time'])) ? 1 : 0;
			
			$info = RC_DB::table('store_franchisee')->where('store_id', $_SESSION['store_id'])->first();
			
			
			$seller_info = array(
	    	  		'id'					=> $info['store_id'],
	    	  		'seller_name'			=> $info['merchants_name'],
	    	  		'seller_logo'			=> /* RC_Upload::upload_url().'/'.$info['shop_logo'] */'',
	    	  		'seller_category'		=> /* $info['cat_name'] */'',
	    	  		'seller_telephone'		=> $info['contact_mobile'],//$info['kf_tel'],
	    	  		'seller_province'		=> $region->where(array('region_id'=>$info['province']))->get_field('region_name'),
	    	  		'seller_city'			=> $region->where(array('region_id'=>$info['city']))->get_field('region_name'),
	    	  		'seller_address'		=> $info['address'],
	    	  		'seller_description'	=> '',//$info['notice'],
					//'validated_status'		=> $info['status'],
					'validated_status'		=> '2',
			);
// 			$result = $this->admin_priv('merchant_setinfo');
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
			$result = $this->admin_priv('shop_config');
			if (is_ecjia_error($result)) {
				$privilege = 1;
			} else {
				$privilege = 3;
			}
		}
// 		EM_Api::outPut($seller_info, null, $privilege);
		return $seller_info;
    }
    
}