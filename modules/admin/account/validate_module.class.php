<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 认证申请
 * @author will.chen
 *
 */
class validate_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) { 
		$ecjia = RC_Loader::load_app_class('api_admin', 'api');
		$ecjia->authadminSession();
    	
		$validate_type = $this->requestData('validate_type', '');
		
		if (empty($validate_type)) {
			return new ecjia_error(101, '错误的参数提交！');
		}
		
		$validate_code = $this->requestData('validate_code', '');
		$time = RC_Time::gmtime();
		if (empty($validate_code) || $_SESSION['merchant_validate_code'] != $validate_code || $_SESSION['merchant_validate_expiry'] < $time) {
			return new ecjia_error('code_error', '验证码不正确！');
		}
		
		//$shop_id = RC_Model::model('seller/seller_shopinfo_model')->where(array('id' => $_SESSION['seller_id']))->get_field('shop_id');
		
		/* 文件路径处理*/
		//$uid = $_SESSION['seller_id'];
		$uid = $_SESSION['store_id'];
		$uid = abs(intval($uid));//保证uid为绝对的正整数
		$uid = sprintf("%09d", $uid);//格式化uid字串， d 表示把uid格式为9位数的整数，位数不够的填0
		$dir1 = substr($uid, 0, 3);//把uid分段
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$filename = md5($uid);
		$path = RC_Upload::upload_path('data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3);
		//创建目录
		RC_Dir::create($path);
		
		$responsible_person 		= $this->requestData('responsible_person', '');
		$identity_type 				= $this->requestData('identity_type', '');
		$identity_number 			= $this->requestData('identity_number', '');
		$personhand_identity_pic    = $this->requestData('identity_pic', '');
		$identity_pic_front 		= $this->requestData('identity_pic_front', '');
		$identity_pic_back  		= $this->requestData('identity_pic_back', '');
		$company_name 				= $this->requestData('company_name', '');
		$business_licence_pic		= $this->requestData('business_licence_pic', '');
		
		$data = array('validate_type' => $validate_type);
		
		if (!empty($responsible_person)) {
			$data['responsible_person'] = $responsible_person;
		}
		
		if (!empty($company_name)) {
			$data['company_name'] = $company_name;
		}
		
		if (!empty($identity_type)) {
			$data['identity_type'] = $identity_type;
		}
		
		if (!empty($identity_number)) {
			$data['identity_number'] = $identity_number;
		}
		
		if (!empty($personhand_identity_pic)) {
			$filename_path = $path.'/'.substr($uid, -2).'_hand_id_'.$filename.'.jpg';
			@unlink($filename_path);
			$personhand_identity_pic = base64_decode($personhand_identity_pic);
			file_put_contents($filename_path, $personhand_identity_pic);
			//$data['identity_pic'] = 'data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_hand_id_'.$filename.'.jpg';
			$data['personhand_identity_pic'] = 'data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_hand_id_'.$filename.'.jpg';
		}
		
		if (!empty($business_licence_pic)) {
			$filename_path = $path.'/'.substr($uid, -2).'_business_licence_'.$filename.'.jpg';
			@unlink($filename_path);
			$business_licence_pic = base64_decode($business_licence_pic);
			file_put_contents($filename_path, $business_licence_pic);
			$data['business_licence_pic'] = 'data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_business_licence_'.$filename.'.jpg';
		}
		
		if (!empty($identity_pic_front)) {
			$filename_path = $path.'/'.substr($uid, -2).'_id_front_'.$filename.'.jpg';
			@unlink($filename_path);
			$identity_pic_front = base64_decode($identity_pic_front);
			file_put_contents($filename_path, $identity_pic_front);
			$data['identity_pic_front'] = 'data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_id_front_'.$filename.'.jpg';
		}
			
		if (!empty($identity_pic_back)) {
			$filename_path = $path.'/'.substr($uid, -2).'_id_back_'.$filename.'.jpg';
			@unlink($filename_path);
			$identity_pic_back = base64_decode($identity_pic_back);
			file_put_contents($filename_path, $identity_pic_back);
			$data['identity_pic_front'] = 'data/merchant/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_id_back_'.$filename.'.jpg';
		}
		//$data['merchant_status'] = 0;
		//$data['merchant_status'] = 1;
		//RC_Model::model('merchant/merchants_shop_information_model')->where(array('shop_id' => $shop_id))->update($data);
		RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), $_SESSION['store_id'])->update($data);
		return array();
    }	
    
}