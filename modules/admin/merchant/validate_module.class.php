<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 入驻申请等信息获取验证码
 * @author
 *
 */
class validate_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
    	$this->authadminSession();
        if ($_SESSION['admin_id'] <= 0 && $_SESSION['staff_id'] <= 0) {
            return new ecjia_error(100, 'Invalid session');
        }
		$type = $this->requestData('type');

		if (empty($type)) {
			return new ecjia_error(101, '错误的参数提交');
		}
		//$shop_id = RC_Model::model('seller/seller_shopinfo_model')->where(array('id' => $_SESSION['seller_id']))->get_field('shop_id');
		//$value = RC_Model::model('merchant/merchants_shop_information_model')->where(array('shop_id' => $shop_id))->get_field('contact_mobile');
		$value = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), $_SESSION['store_id'])->pluck('contact_mobile');
		$code = rand(100000, 999999);

		if ($type == 'mobile' && !empty($value)) {
			$result = ecjia_app::validate_application('sms');
			/* 判断是否有短信app*/
			if (!is_ecjia_error($result)) {
				//发送短信
				$tpl_name = 'sms_verifying_authentication';
				$tpl   = RC_Api::api('sms', 'sms_template', $tpl_name);
				/* 判断短信模板是否存在*/
				if (!empty($tpl)) {
					ecjia_api::$controller->assign('action', __('申请入驻认证'));
					ecjia_api::$controller->assign('code', $code);
					ecjia_api::$controller->assign('service_phone', ecjia::config('service_phone'));

					$content = ecjia_api::$controller->fetch_string($tpl['template_content']);
					$options = array(
							'mobile' 		=> $value,
							'msg'			=> $content,
							'template_id' 	=> $tpl['template_id'],
					);
                    $time = RC_Time::gmtime();
					$response = RC_Api::api('sms', 'sms_send', $options);
				}
			}
		} else {
			return new ecjia_error('mobile_error', '手机号码不能为空！');
		}
// 		/* 邮箱找回密码*/
// 		if ($type == 'email') {
// 			$tpl_name = 'email_verifying_authentication';
// 			$tpl   = RC_Api::api('mail', 'mail_template', $tpl_name);
// 			/* 判断短信模板是否存在*/
// 			if (!empty($tpl)) {
// 				ecjia_api::$view_object->assign('action', __('通过短信找回密码'));
// 				ecjia_api::$view_object->assign('code', $code);
// 				ecjia_api::$view_object->assign('service_phone', ecjia::config('service_phone'));
// 				$content = ecjia_api::$controller->fetch_string($tpl['template_content']);
// 				$response = RC_Mail::send_mail(ecjia::config('shop_name'), ecjia::config('service_email'), $tpl['template_subject'], $content, $tpl['is_html']);

// 			}
// 		}

		/* 判断是否发送成功*/
		if ($response === true) {
			$time = RC_Time::gmtime();
            $_SESSION['merchant_validate_code'] = $code;
            $_SESSION['merchant_validate_expiry'] = $time + 600;//设置有效期10分钟
			return array('data' => '验证码发送成功！');
		} else {
			return new ecjia_error('send_code_error', __('验证码发送失败！'));
		}
    }

}
