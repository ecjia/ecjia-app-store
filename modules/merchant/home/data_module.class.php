<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺首页信息
 * @author will.chen
 *
 */
class data_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {

    	$this->authSession();
		$seller_id = $this->requestData('seller_id');
		$location = $this->requestData('location', array());
// 		$location = array(
// 				'latitude'	=> '31.235450744628906',
// 				'longitude' => '121.41641998291016',
// 		);
		if (empty($seller_id)) {
			return new ecjia_error( 'invalid_parameter', RC_Lang::get ('system::system.invalid_parameter' ));
		}
		RC_Loader::load_app_func('common', 'goods');
		RC_Loader::load_app_func('goods', 'goods');

// 		$msi_dbview = RC_Loader::load_app_model('merchants_shop_information_viewmodel', 'seller');
		// $ssi_dbview = RC_Model::model('store/seller_shopinfo_viewmodel');

		$where = array();
		// $where['ssi.status'] = 1;
// 		$where['msi.merchants_audit'] = 1;
		// $where['ssi.id'] = $seller_id;

		$user_id = $_SESSION['user_id'];
		$user_id = empty($user_id) ? 0 : $user_id;

// 		$field ='ssi.*, ssi.id as seller_id, ssi.shop_name as seller_name, tr.item_value1, tr.item_value2,  sc.cat_name, count(cs.seller_id) as follower, SUM(IF(cs.user_id = '.$user_id.',1,0)) as is_follower';
// // 		$info = $msi_dbview->join(array('category', 'seller_shopinfo', 'collect_store', 'term_relationship'))
// 		$info = $ssi_dbview->join(array('seller_category', 'collect_store', 'term_relationship'))
// 							->field($field)
// 							->where($where)
// 							->find();
        $info = RC_DB::table('store_franchisee as sf')
        ->leftJoin('store_category as sc', RC_DB::raw('sf.cat_id'), '=', RC_DB::raw('sc.cat_id'))
        ->leftJoin('collect_store as cs', RC_DB::raw('sf.cat_id'), '=', RC_DB::raw('cs.store_id'))
        ->selectRaw('sf.*, sc.cat_name, count(cs.store_id) as follower, SUM(IF(cs.user_id = '.$user_id.',1,0)) as is_follower')
        ->where(RC_DB::raw('sf.status'), 1)->where(RC_DB::raw('sf.store_id'), $seller_id)
        ->first();
        $store_config = array(
            'shop_title'                => '', // 店铺标题
            'shop_kf_mobile'            => '', // 客服手机号码
            'shop_kf_email'             => '', // 客服邮件地址
//             'shop_kf_qq'                => '', // 客服QQ号码
//             'shop_kf_ww'                => '', // 客服淘宝旺旺
//             'shop_kf_type'              => '', // 客服样式
            'shop_logo'                 => '', // 默认店铺页头部LOGO
            'shop_banner_pic'           => '', // banner图
            'shop_trade_time'           => '', // 营业时间
            'shop_description'          => '', // 店铺描述
            'shop_notice'               => '', // 店铺公告
        );
        $config = RC_DB::table('merchants_config')->where('store_id', $seller_id)->select('code', 'value')->get();
        foreach ($config as $key => $value) {
            $store_config[$value['code']] = $value['value'];
        }
        $info = array_merge($info, $store_config);

		if(substr($info['shop_logo'], 0, 1) == '.') {
			$info['shop_logo'] = str_replace('../', '/', $info['shop_logo']);
		}

		$goods_db = RC_Model::model('goods/goods_model');
		$gfield = 'count(*) as count, SUM(IF(is_new=1, 1, 0)) as new_goods, SUM(IF(is_best=1, 1, 0)) as best_goods, SUM(IF(is_hot=1, 1, 0)) as hot_goods';
// 		$count_where = array('user_id' => $seller_id, 'is_on_sale' => 1, 'is_alone_sale' => 1, 'is_delete' => 0);
		$count_where = array('store_id' => $seller_id, 'is_on_sale' => 1, 'is_alone_sale' => 1, 'is_delete' => 0);
		if(ecjia::config('review_goods') == 1){
			$count_where['review_status'] = array('gt' => 2);
		}
		$goods_count = $goods_db->field($gfield)->where($count_where)->find();


		//推荐商品查询 start
		$mobilebuy_db = RC_Model::model('goods/goods_activity_model');
		$goods_options = array('page' => 1, 'size' => 3, 'seller_id' => $seller_id);

		/* 新品*/
		$newgoods_result = RC_Api::api('goods', 'goods_list', array_merge(array('intro' => 'new'), $goods_options));
		$newgoods_list = array();
		if (!empty($newgoods_result['list'])) {
			foreach ($newgoods_result['list'] as $val) {
				/* 判断是否有促销价格*/
				$price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_promote_price'] : $val['unformatted_shop_price'];
				$activity_type = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? 'PROMOTE_GOODS' : 'GENERAL_GOODS';
				/* 计算节约价格*/
				$saving_price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_shop_price'] - $val['unformatted_promote_price'] : (($val['unformatted_market_price'] > 0 && $val['unformatted_market_price'] > $val['unformatted_shop_price']) ? $val['unformatted_market_price'] - $val['unformatted_shop_price'] : 0);

				$mobilebuy_price = $object_id = 0;
				/* if (!is_ecjia_error($result_mobilebuy) && $is_active) {
					$mobilebuy = $mobilebuy_db->find(array(
							'goods_id'	 => $val['goods_id'],
							'start_time' => array('elt' => RC_Time::gmtime()),
							'end_time'	 => array('egt' => RC_Time::gmtime()),
							'act_type'	 => GAT_MOBILE_BUY,
					));
					if (!empty($mobilebuy)) {
						$ext_info = unserialize($mobilebuy['ext_info']);
						$mobilebuy_price = $ext_info['price'];
						if ($mobilebuy_price < $price) {
							$val['promote_price'] = price_format($mobilebuy_price);
							$object_id		= $mobilebuy['act_id'];
							$activity_type	= 'MOBILEBUY_GOODS';
							$saving_price = ($val['unformatted_shop_price'] - $mobilebuy_price) > 0 ? $val['unformatted_shop_price'] - $mobilebuy_price : 0;
						}
					}
				} */

				$newgoods_list[] = array(
						'id'			=> $val['goods_id'],
						'name'			=> $val['name'],
						'market_price'	=> $val['market_price'],
						'shop_price'	=> $val['shop_price'],
						'promote_price'	=> $val['promote_price'],
						'img' => array(
								'thumb'	=> $val['goods_img'],
								'url'	=> $val['original_img'],
								'small'	=> $val['goods_thumb']
						),
						'activity_type' => $activity_type,
						'object_id'		=> $object_id,
						'saving_price'	=> $saving_price,
						'formatted_saving_price' => $saving_price > 0 ? '已省'.$saving_price.'元' : '',
				);
			}
		}

		/* 热销*/
		$hotgoods_result = RC_Api::api('goods', 'goods_list', array_merge(array('intro' => 'hot'), $goods_options));
		$hotgoods_list = array();
		if (!empty($hotgoods_result['list'])) {
			foreach ($hotgoods_result['list'] as $val) {
				/* 判断是否有促销价格*/
				$price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_promote_price'] : $val['unformatted_shop_price'];
				$activity_type = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? 'PROMOTE_GOODS' : 'GENERAL_GOODS';
				/* 计算节约价格*/
				$saving_price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_shop_price'] - $val['unformatted_promote_price'] : (($val['unformatted_market_price'] > 0 && $val['unformatted_market_price'] > $val['unformatted_shop_price']) ? $val['unformatted_market_price'] - $val['unformatted_shop_price'] : 0);

				$mobilebuy_price = $object_id = 0;
				/* if (!is_ecjia_error($result_mobilebuy) && $is_active) {
					$mobilebuy = $mobilebuy_db->find(array(
							'goods_id'	 => $val['goods_id'],
							'start_time' => array('elt' => RC_Time::gmtime()),
							'end_time'	 => array('egt' => RC_Time::gmtime()),
							'act_type'	 => GAT_MOBILE_BUY,
					));
					if (!empty($mobilebuy)) {
						$ext_info = unserialize($mobilebuy['ext_info']);
						$mobilebuy_price = $ext_info['price'];
						if ($mobilebuy_price < $price) {
							$val['promote_price'] = price_format($mobilebuy_price);
							$object_id		= $mobilebuy['act_id'];
							$activity_type	= 'MOBILEBUY_GOODS';
							$saving_price = ($val['unformatted_shop_price'] - $mobilebuy_price) > 0 ? $val['unformatted_shop_price'] - $mobilebuy_price : 0;
						}
					}
				} */

				$hotgoods_list[] = array(
						'id'			=> $val['goods_id'],
						'name'			=> $val['name'],
						'market_price'	=> $val['market_price'],
						'shop_price'	=> $val['shop_price'],
						'promote_price'	=> $val['promote_price'],
						'img' => array(
								'thumb'	=> $val['goods_img'],
								'url'	=> $val['original_img'],
								'small'	=> $val['goods_thumb']
						),
						'activity_type' => $activity_type,
						'object_id'		=> $object_id,
						'saving_price'	=> $saving_price,
						'formatted_saving_price' => $saving_price > 0 ? '已省'.$saving_price.'元' : '',
				);
			}
		}

		/* 精品*/
		$bestgoods_result = RC_Api::api('goods', 'goods_list', array_merge(array('intro' => 'best'), $goods_options));
		$bestgoods_list = array();
		if (!empty($bestgoods_result['list'])) {
			foreach ($bestgoods_result['list'] as $val) {
				/* 判断是否有促销价格*/
				$price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_promote_price'] : $val['unformatted_shop_price'];
				$activity_type = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? 'PROMOTE_GOODS' : 'GENERAL_GOODS';
				/* 计算节约价格*/
				$saving_price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_shop_price'] - $val['unformatted_promote_price'] : (($val['unformatted_market_price'] > 0 && $val['unformatted_market_price'] > $val['unformatted_shop_price']) ? $val['unformatted_market_price'] - $val['unformatted_shop_price'] : 0);

				$mobilebuy_price = $object_id = 0;
				/* if (!is_ecjia_error($result_mobilebuy) && $is_active) {
					$mobilebuy = $mobilebuy_db->find(array(
							'goods_id'	 => $val['goods_id'],
							'start_time' => array('elt' => RC_Time::gmtime()),
							'end_time'	 => array('egt' => RC_Time::gmtime()),
							'act_type'	 => GAT_MOBILE_BUY,
					));
					if (!empty($mobilebuy)) {
						$ext_info = unserialize($mobilebuy['ext_info']);
						$mobilebuy_price = $ext_info['price'];
						if ($mobilebuy_price < $price) {
							$val['promote_price'] = price_format($mobilebuy_price);
							$object_id		= $mobilebuy['act_id'];
							$activity_type	= 'MOBILEBUY_GOODS';
							$saving_price = ($val['unformatted_shop_price'] - $mobilebuy_price) > 0 ? $val['unformatted_shop_price'] - $mobilebuy_price : 0;
						}
					}
				} */

				$bestgoods_list[] = array(
						'id'			=> $val['goods_id'],
						'name'			=> $val['name'],
						'market_price'	=> $val['market_price'],
						'shop_price'	=> $val['shop_price'],
						'promote_price'	=> $val['promote_price'],
						'img' => array(
								'thumb'	=> $val['goods_img'],
								'url'	=> $val['original_img'],
								'small'	=> $val['goods_thumb']
						),
						'activity_type' => $activity_type,
						'object_id'		=> $object_id,
						'saving_price'	=> $saving_price,
						'formatted_saving_price' => $saving_price > 0 ? '已省'.$saving_price.'元' : '',
				);
			}
		}


// 		$db_goods_view = RC_Model::model('goods/comment_viewmodel');

// 		$field = 'count(*) as count, SUM(comment_rank) as comment_rank, SUM(comment_server) as comment_server, SUM(comment_delivery) as comment_delivery';
// 		$comment = $db_goods_view->join(null)->field($field)->where(array('c.seller_id' => $seller_id, 'parent_id' => 0, 'status' => 1))->find();

		//导航距离计算
		//表更换
// 		$db_term_relation = RC_Model::model('goods/term_relationship_model');
// 		$relation_row = $db_term_relation->where(array('object_id' => $info['seller_id'], 'object_type' => 'ecjia.merchant', 'object_group' => 'merchant'))->find();

		$distance = (!empty($location['latitude']) && !empty($location['longitude']) && !empty($info)) ? getDistance($info['item_value2'], $info['item_value1'], $location['latitude'], $location['longitude']) : '';

		$db_region = RC_Model::model('shipping/region_model');
		$province_name = $db_region->where(array('region_id' => $info['province']))->get_field('region_name');
		$city_name = $db_region->where(array('region_id' => $info['city']))->get_field('region_name');

		$db_favourable = RC_Model::model('favourable/favourable_activity_model');
		$favourable_result = $db_favourable->where(array('store_id' => $info['seller_id'], 'start_time' => array('elt' => RC_Time::gmtime()), 'end_time' => array('egt' => RC_Time::gmtime()), 'act_type' => array('neq' => 0)))->select();
		$favourable_list = array();
		if (empty($rec_type)) {
			if (!empty($favourable_result)) {
				foreach ($favourable_result as $val) {
					if ($val['act_range'] == '0') {
						$favourable_list[] = array(
								'name' => $val['act_name'],
								'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
								'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
						);
					} else {
						$act_range_ext = explode(',', $val['act_range_ext']);
						switch ($val['act_range']) {
							case 1 :
								if (in_array($val['cat_id'], $act_range_ext)) {
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
								}
								break;
							case 2 :
								if (in_array($val['brand_id'], $act_range_ext)) {
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
								}
								break;
							case 3 :
								if (in_array($val['goods_id'], $act_range_ext)) {
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
								}
								break;
							default:
								break;
						}
					}

				}
			}
		}

		$seller_info = array(
				'id'				=> $info['store_id'],
				'seller_name'		=> $info['merchants_name'],
				'seller_logo'		=> empty($info['shop_logo']) ?  '' : RC_Upload::upload_url($info['shop_logo']),
		        'seller_banner'		=> empty($info['shop_banner_pic']) ?  '' : RC_Upload::upload_url($info['shop_banner_pic']),
				'seller_category'	=> $info['cat_name'],
				'shop_name'			=> $info['company_name'],
				'shop_address'		=> $province_name.' '.$city_name.' '.$info['address'],
				'telephone'			=> $info['shop_kf_mobile'],
				'seller_qq'			=> $info['shop_kf_qq'],
				'seller_description'	=> $info['shop_notice'],
				'follower'			=> $info['follower'],
				'is_follower'		=> $info['is_follower'],
				'location'			=> array(
						'longitude' => $info['longitude'],
						'latitude'	=> $info['latitude'],
						'distance'	=> $distance,
				),
				'goods_count'		=> array(
						'count'			=> $goods_count['count'],
						'new_goods'		=> $goods_count['new_goods'],
						'best_goods'	=> $goods_count['best_goods'],
						'hot_goods'		=> $goods_count['hot_goods'],
				),
				'comment' 	=> array(
				    'comment_goods'			=> '100%',
				    'comment_server'		=> '100%',
				    'comment_delivery'		=> '100%',
// 						'comment_goods'			=> $comment['count'] > 0 ? round($comment['comment_rank']/($comment['count']*5)*100).'%' : '100%',
// 						'comment_server'		=> $comment['count'] > 0 ? round($comment['comment_server']/($comment['count']*5)*100).'%' : '100%',
// 						'comment_delivery'		=> $comment['count'] > 0 ? round($comment['comment_delivery']/($comment['count']*5)*100).'%' : '100%',
				),
				'new_goods'			=> $newgoods_list,
				'hot_goods'			=> $hotgoods_list,
				'best_goods'		=> $bestgoods_list,
				'favourable_list'	=> $favourable_list
		);

		return $seller_info;
	}
}


/**
 * 计算两组经纬度坐标 之间的距离
 * @param params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
 * @return return m or km
 */
function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 1) {
// 	define('EARTH_RADIUS', 6378.137);//地球半径
// 	define('PI', 3.1415926);
	$EARTH_RADIUS = 6378.137;
	$PI = 3.1415926;
	$radLat1 = $lat1 * $PI / 180.0;
	$radLat2 = $lat2 * $PI / 180.0;
	$a = $radLat1 - $radLat2;
	$b = ($lng1 * $PI / 180.0) - ($lng2 * $PI / 180.0);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
	$s = $s * $EARTH_RADIUS;
	$s = round($s * 1000);
	if ($len_type > 1) {
		$s /= 1000;
	}

	return round($s, $decimal);
}

// end
