<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺街列表
 * @author will.chen
 *
 */
class list_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {

		$seller_categroy	= $this->requestData('seller_category');
		$goods_category		= $this->requestData('goods_category', 0);
		$keywords	 = $this->requestData('keywords');
		$location	 = $this->requestData('location', array());
		/*经纬度为空判断*/
		if (!is_array($location) || empty($location['longitude']) || empty($location['latitude'])) {
			$seller_list = array();
			$page = array(
				'total'	=> '0',
				'count'	=> '0',
				'more'	=> '0',
			);
			return array('data' => $seller_list, 'pager' => $page);
		} else {
            $geohash = RC_Loader::load_app_class('geohash', 'store');
            $geohash_code = $geohash->encode($location['latitude'] , $location['longitude']);
            $geohash_code = substr($geohash_code, 0, 5);
        }
		/* 获取数量 */
		$size = $this->requestData('pagination.count', 15);
		$page = $this->requestData('pagination.page', 1);

		$store_id_group = RC_Api::api('store', 'neighbors_store_id', array('geohash' => $geohash_code));
		
		$options = array(
				'seller_category'	=> $seller_categroy,
				'goods_category'	=> $goods_category,
				'keywords'		=> $keywords,
				'size'			=> $size,
				'page'			=> $page,
				'store_id_group' => $store_id_group,
				'sort'			=> array('sort_order' => 'asc'),
		);
		//TODO ::增加店铺缓存
		$result = RC_Api::api('store', 'store_list', $options);

		$seller_list = array();
		if (!empty($result['seller_list'])) {
			$max_goods = 0;

			$collect_store_id = RC_DB::table('collect_store')->where('user_id', $_SESSION['user_id'])->lists('store_id');
			
			$db_favourable = RC_Model::model('favourable/favourable_activity_model');
			/* 手机专享*/
			foreach ($result['seller_list'] as $row) {
				$favourable_list = array();
				
				//TODO ::增加优惠活动
				$favourable_result = $db_favourable->where(array('store_id' => $row['id'], 'start_time' => array('elt' => RC_Time::gmtime()), 'end_time' => array('egt' => RC_Time::gmtime()), 'act_type' => array('neq' => 0)))->select();
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
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
									break;
								case 2 :
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
									break;
								case 3 :
									$favourable_list[] = array(
											'name' => $val['act_name'],
											'type' => $val['act_type'] == '1' ? 'price_reduction' : 'price_discount',
											'type_label' => $val['act_type'] == '1' ? __('满减') : __('满折'),
									);
									break;
								default:
									break;
							}
						}
					}
				}

				//TODO ::增加商品缓存
				$goods_options = array('store_id' => $row['id'], 'cat_id' => $goods_category, 'keywords' => $keywords, 'page' => 1, 'size' => 10);
				
				/* 如有查询添加，不限制分页*/
				if (!empty($goods_category) || !empty($keywords)) {
					$goods_options['size'] = $goods_options['page'] = 0;
				}
				
				$goods_result = RC_Api::api('goods', 'goods_list', $goods_options);
				$goods_list = array();
				if (!empty($goods_result['list'])) {
					foreach ($goods_result['list'] as $val) {
						/* 判断是否有促销价格*/
						$price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_promote_price'] : $val['unformatted_shop_price'];
						$activity_type = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? 'PROMOTE_GOODS' : 'GENERAL_GOODS';
						/* 计算节约价格*/
						$saving_price = ($val['unformatted_shop_price'] > $val['unformatted_promote_price'] && $val['unformatted_promote_price'] > 0) ? $val['unformatted_shop_price'] - $val['unformatted_promote_price'] : (($val['unformatted_market_price'] > 0 && $val['unformatted_market_price'] > $val['unformatted_shop_price']) ? $val['unformatted_market_price'] - $val['unformatted_shop_price'] : 0);



						$goods_list[] = array(
								'goods_id'		=> $val['goods_id'],
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
								'object_id'		=> 0,
								'saving_price'	=>	$saving_price,
								'formatted_saving_price' => $saving_price > 0 ? '已省'.$saving_price.'元' : '',
						);
					}
				}
				
				$seller_list[] = array(
						'id'				=> $row['id'],
						'seller_name'		=> $row['seller_name'],
						'seller_category'	=> $row['seller_category'],
						'manage_mode'		=> $row['manage_mode'],
						'seller_logo'		=> $row['shop_logo'],
						'seller_goods'		=> $goods_list,
						'follower'			=> $row['follower'],
						'is_follower'		=> in_array($row['id'], $collect_store_id) ? 1 : 0,
						'goods_count'       => $goods_result['page']->total_records,
						'favourable_list'	=> $favourable_list,
				);
			}
		}
		$page = array(
				'total'	=> $result['page']->total_records,
				'count'	=> $result['page']->total_records,
				'more'	=> $result['page']->total_pages <= $page ? 0 : 1,
		);

		return array('data' => $seller_list, 'pager' => $page);
	}
}


// end
