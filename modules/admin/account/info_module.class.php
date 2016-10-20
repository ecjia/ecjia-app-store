<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 认证申请
 * @author will.chen
 *
 */
class info_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) { 
		$ecjia = RC_Loader::load_app_class('api_admin', 'api');
		$ecjia->authadminSession();
		
    	
		//$shop_id = RC_Model::model('seller/seller_shopinfo_model')->where(array('id' => $_SESSION['seller_id']))->get_field('shop_id');
		//$merchant_info = RC_Model::model('merchant/merchants_shop_information_model')->where(array('shop_id' => $shop_id))->find();
		$merchant_info = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), $_SESSION['store_id'])->first();
		/* 文件路径处理*/
		//$uid = $_SESSION['seller_id'];
		$uid = $_SESSION['store_id'];
		$uid = abs(intval($uid));//保证uid为绝对的正整数
		$uid = sprintf("%09d", $uid);//格式化uid字串， d 表示把uid格式为9位数的整数，位数不够的填0
		$dir1 = substr($uid, 0, 3);//把uid分段
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$filename = md5($uid);
		$path = RC_Upload::upload_path('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/');
		
		/* 判断身份正面图片*/
		if(!file_exists($path.substr($uid, -2).'_id_front_'.$filename.'.jpg')) {
			$identity_pic_front = '';
		} else {
			$identity_pic_front = RC_Upload::upload_url('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_id_front_'.$filename.'.jpg');
		}
		
		/* 判断身份反面图片*/
		if(!file_exists($path.substr($uid, -2).'_id_back_'.$filename.'.jpg')) {
			$identity_pic_back = '';
		} else {
			$identity_pic_back = RC_Upload::upload_url('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_id_back_'.$filename.'.jpg');
		}
		
		/* 个人认证*/
		if ($merchant_info['validate_type'] == 1) {
			/* 判断手持身份图片*/
			if(!file_exists($path.substr($uid, -2).'_hand_id_'.$filename.'.jpg')) {
				$identity_pic = '';
			} else {
				$identity_pic = RC_Upload::upload_url('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_hand_id_'.$filename.'.jpg');
			}
			return array(
					'validate_type'			=> $merchant_info['validate_type'],
					'responsible_person'	=> $merchant_info['responsible_person'],
					'identity_type'			=> $merchant_info['identity_type'],
					'identity_number'		=> $merchant_info['identity_number'],
					'identity_pic' 			=> $identity_pic,
					'identity_pic_front'	=> $identity_pic_front,
					'identity_pic_back'		=> $identity_pic_back,
					'contact_mobile'		=> $merchant_info['contact_mobile'],
					
			);
		} else {
			/* 判断营业执照图片*/
			if(!file_exists($path.substr($uid, -2).'_business_licence_'.$filename.'.jpg')) {
				$business_licence_pic = '';
			} else {
				$business_licence_pic = RC_Upload::upload_url('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_business_licence_'.$filename.'.jpg');
			}
			return array(
					'validate_type'			=> $merchant_info['validate_type'],
					'responsible_person'	=> $merchant_info['responsible_person'],
					'company_name'			=> $merchant_info['company_name'],
					'business_licence_pic'	=> $business_licence_pic,
					'identity_pic_front'	=> $identity_pic_front,
					'identity_pic_back'		=> $identity_pic_back,
					'contact_mobile'		=> $merchant_info['contact_mobile'],
			);
		}
    }
}