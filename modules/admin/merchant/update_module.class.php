<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺update信息
 * @author luchongchong
 *
 */
class update_module implements ecjia_interface
{
 	
    public function run(ecjia_api & $api)
    { 
    	$ecjia = RC_Loader::load_app_class('api_admin', 'api');
    	$ecjia->authadminSession();
    	
    	$ssi_db				= RC_Loader::load_app_model('seller_shopinfo_model', 'seller');
    	$msi_category_db 	= RC_Loader::load_app_model('merchants_shop_information_model', 'seller');
		$seller_category	= isset($_POST['seller_category']) ? $_POST['seller_category'] : null;
		$seller_telephone	= isset($_POST['seller_telephone']) ? $_POST['seller_telephone'] : null;
		$province			= isset($_POST['provice']) ? $_POST['provice'] :null;
		$city				= isset($_POST['city']) ? $_POST['city'] : null;
		$seller_address		= isset($_POST['seller_address']) ? $_POST['seller_address'] : null;
		$seller_description	= isset($_POST['seller_description']) ? $_POST['seller_description'] : null;
		
		if ($_SESSION['ru_id'] > 0) {
			$result = $ecjia->admin_priv('merchant_setinfo');
			if (is_ecjia_error($result)) {
				EM_Api::outPut($result);
			}
			RC_Loader::load_app_func('global', 'merchant');
			assign_adminlog_contents();
			$where1['user_id'] =$_SESSION['ru_id'];
			$where2['ru_id'] = $_SESSION['ru_id'];
			 
			$data_category = array();
			if (isset($seller_category)) {
				$data_category = array(
						'shop_categoryMain'		=> $seller_category
				);
			}
			
			$data_shopinfo = array();
			if (isset($seller_telephone)) {
			 	$data_shopinfo['kf_tel'] = $seller_telephone;
			}
			if (isset($province)) {
			 	$data_shopinfo['province'] = $province;
			}
			if (isset($city)) {
			 	$data_shopinfo['city'] = $city;
			}	
			if (isset($seller_address)) {
				$data_shopinfo['shop_address'] = $seller_address;
			}
			if (isset($seller_description)) {
				$data_shopinfo['notice'] = $seller_description;
			}
			
			$count_category = $msi_category_db->where($where1)->update($data_category);
			$count_shopinfo = $ssi_db->where($where2)->update($data_shopinfo);
			ecjia_admin::admin_log('店铺设置>基本信息设置【来源掌柜】', 'edit', 'merchant');
	    	return true;
	    	
		} else {
			$result = $ecjia->admin_priv('shop_config');
			if (is_ecjia_error($result)) {
				EM_Api::outPut($result);
			}
			if (isset($seller_telephone)) {
				ecjia_config::instance()->write_config('service_phone', $seller_telephone);
			}
			if(isset($province)){
				ecjia_config::instance()->write_config('shop_province', $province);
			}
			if(isset($city)){
				ecjia_config::instance()->write_config('shop_city', $city);
			}
			if (isset($seller_address)) {
				ecjia_config::instance()->write_config('shop_address', $seller_address);
			}
			if (isset($seller_description)) {
				ecjia_config::instance()->write_config('shop_notice', $seller_description);
			}
			ecjia_admin::admin_log('控制面板>系统设置>商店设置【来源掌柜】', 'edit', 'shop_config');
			return true;
		}
    }	
    
}