<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
namespace Ecjia\App\Store;

use RC_DB;
use RC_Time;
use ecjia;
use RC_Api;
use RC_Logger;
use ecjia_page;
use RC_Lang;
use RC_Upload;

/**
 * 店铺信息
 *
 */
class StoreFranchisee
{
	
    /**
     * 获取店铺信息 
     * @param array $options
     * @return array
     */
    public static function StoreFranchiseeInfo($options) {
    	$dbview = RC_DB::table('store_franchisee as sf')->leftJoin('store_category as sc', RC_DB::raw('sf.cat_id'), '=', RC_DB::raw('sc.cat_id'));
    	if (isset($options['status'])) {
    		$dbview->where(RC_DB::raw('sf.status'), $options['status']);
    	}
    	if (isset($options['store_id'])) {
    		$dbview->where(RC_DB::raw('sf.store_id'), $options['store_id']);
    	}
    	$info = $dbview->select(RC_DB::raw($options['field']))->first();
    	return $info;
    }
    
    /**
     * 获取店铺营业时间（格式化）
     * @param int $store_id
     * @return string
     */
    public static function GetStoreTradetime($store_id = 0) {
    	if (empty($store_id)) {
    		$store_id = $_SESSION['store_id'];
    	}
    	if (empty($store_id)) {
    		return false;
    	}
    
    	$trade_time = self::GetMerchantConfig('shop_trade_time', '', $store_id);
    	if (empty($trade_time)) {
    		return __('暂未设置', 'store');
    	}
    	$trade_time = unserialize($trade_time);
    	if (empty($trade_time)) {
    		return __('暂未设置', 'store');
    	}
    	$sart_time = $trade_time['start'];
    	$end_time = explode(':', $trade_time['end']);
    	if ($end_time[0] >= 24) {
    		$end_time[0] = __('次日', 'store'). ($end_time[0] - 24);
    	}
    
    	return $sart_time . '--' . $end_time[0] . ':' . $end_time[1];
    }
    
    /**
     * 获取店铺配置信息
     * @param string $code
     * @param string or array $arr
     * @param int $store_id
     * @return array or string
     */
    public static function GetMerchantConfig($code = '', $arr = '', $store_id = 0)
    {
    	if (empty($store_id)) {
    		$store_id = $_SESSION['store_id'];
    	}
    	if (empty($store_id)) {
    		return array();
    	}
    	if (empty($code)) {
    		if (is_array($arr)) {
    			$config = RC_DB::table('merchants_config')->where('store_id', $store_id)->select('code', 'value')->get();
    			foreach ($config as $key => $value) {
    				$arr[$value['code']] = $value['value'];
    			}
    			return $arr;
    		} else {
    			return;
    		}
    	} else {
    		$config = RC_DB::table('merchants_config')->where('store_id', $store_id)->where('code', $code)->value('value');
    		return $config;
    	}
    }
    
    
    /**
     * 获取店铺名称
     * @param int $store_id
     * @return string
     */
    public static function StoreName($store_id) {
    	$store_name = RC_DB::table('store_franchisee')->where('store_id', $store_id)->value('merchants_name');
    	return $store_name;
    }
    
    /**
     * 获取店铺logo
     * @param int $store_id
     * @return string
     */
    public static function StoreLogo($store_id) {
    	$store_logo = RC_DB::table('merchants_config')->where('store_id', $store_id)->where('code', 'shop_logo')->value('value');
    	if (!empty($store_logo)) {
    		$store_logo = RC_Upload::upload_url($store_logo);
    	} else {
    		$store_logo = '';
    	}
    	return $store_logo;
    }
}
