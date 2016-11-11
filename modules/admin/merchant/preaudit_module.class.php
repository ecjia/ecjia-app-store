<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 入驻申请等信息获取验证码
 * @author
 *
 */
class preaudit_module extends api_admin implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
        $this->authadminSession();
		$value		= $this->requestData('mobile');
		$validate_code	= $this->requestData('validate_code');

		if (empty($validate_code) || empty($value)) {
			return new ecjia_error( 'invalid_parameter', RC_Lang::get ('system::system.invalid_parameter' ));
		}

        if (!empty($validate_code)) {
			/* 判断校验码*/
			if ($_SESSION['merchant_validate_code'] != $validate_code) {
				return new ecjia_error('validate_code_error', '校验码错误！');
			} elseif ($_SESSION['merchant_validate_expiry'] < RC_Time::gmtime()) {
				return new ecjia_error('validate_code_time_out', '校验码已过期！');
			} elseif ($_SESSION['merchant_validate_mobile'] != $value){
                return new ecjia_error('validate_mobile_error', '手机号码错误！');
            }

            // $value = '18265198509';
            $data = RC_DB::table('store_preaudit')->where('contact_mobile', '=', $value)->first();
            // _dump($data,1);
            return array();
		}
    }

}
