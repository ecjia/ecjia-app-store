<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 获取店铺基本信息
 * @return  array
 */
function get_merchant_info($store_id){
    $data = array(
        'shop_kf_mobile'            => '', // 客服手机号码
        'shop_nav_background'		=> '', //店铺导航背景图
        'shop_logo'                 => '', // 默认店铺页头部LOGO
        'shop_banner_pic'           => '', // banner图
        'shop_trade_time'           => '', // 营业时间
        'shop_description'          => '', // 店铺描述
        'shop_notice'               => '', // 店铺公告
    );

    $data = get_merchant_config($store_id, '', $data);
    if(!empty($data['shop_trade_time'])){
        $shop_time = unserialize($data['shop_trade_time']);
        unset($data['shop_trade_time']);
        $sart_time = explode(':', $shop_time['start']);
        $end_time = explode(':', $shop_time['end']);
        $s_time = ($sart_time[0]*60)+$sart_time[1];
        $e_time = ($end_time[0]*60)+$end_time[1];
    }else{
        // 默认时间点 8:00-21:00
        $s_time = 480;
        $e_time = 1260;
    }


    $data['shop_trade_time']    = implode('--', $shop_time);
    $data['shop_nav_background']= !empty($data['shop_nav_background'])? RC_Upload::upload_url($data['shop_nav_background']) : '';
    $data['shop_logo']          = !empty($data['shop_logo'])? RC_Upload::upload_url($data['shop_logo']) : '';
    $data['shop_banner_pic']    = !empty($data['shop_banner_pic'])? RC_Upload::upload_url($data['shop_banner_pic']) : '';
    $data['shop_time_value']    = $s_time.",".$e_time;
    return $data;
}

/**
 * 获取店铺配置信息
 * @return  array
 */
function get_merchant_config($store_id, $code, $arr){
    if(empty($code)){
        if(is_array($arr)){
            $config = RC_DB::table('merchants_config')->where('store_id', $store_id)->select('code','value')->get();
            foreach ($config as $key => $value) {
                $arr[$value['code']] = $value['value'];
            }
            return $arr;
        }else{
            return ;
        }
    }else{
        $config = RC_DB::table('merchants_config')->where('store_id', $store_id)->where('code', '=', $code))->pluck('value');
        return $config;
    }
}
