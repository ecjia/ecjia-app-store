<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商家商品分类（只取一级分类）
 * @author zrl
 *
 */
class ordering_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	
    	$this->authSession();	
		$seller_id = $this->requestData('seller_id', 0);
		$filter = $this->requestData('filter', array());
		$keyword = RC_String::unicode2string($filter['keywords']);
		$sort_type = $filter['sort_by'];
		
		switch ($sort_type) {
			case 'new' :
				$order_by = array('g.sort_order' => 'asc', 'goods_id' => 'desc');
				break;
			case 'price_desc' :
				$order_by = array('shop_price' => 'desc', 'g.sort_order' => 'asc');
				break;
			case 'price_asc' :
				$order_by = array('shop_price' => 'asc', 'g.sort_order' => 'asc');
				break;
			case 'last_update' :
				$order_by = array('last_update' => 'desc');
				break;
			case 'hot' :
				$order_by = array('is_hot' => 'desc', 'click_count' => 'desc', 'g.sort_order' => 'asc');
				break;
			default :
				$order_by = array('g.sort_order' => 'asc', 'goods_id' => 'desc');
				break;
		}
		
		
		if (empty($seller_id)) {
			return new ecjia_error('invalid_parameter', '参数无效');
		}
		$where = array();
		$where['is_show'] = '1';
		$where['parent_id'] = '0';
		$where['seller_id'] = $seller_id;
		$options = array('where' => $where);
		
		$cache_id = sprintf('%X', crc32($seller_id . '-' . $where . '-' .$filter. '-' . $sort_type. '-'. $keyword));
		$cache_key = 'api_ordering'.'_'.$cache_id;
		
		$data = RC_Cache::app_cache_get($cache_key, 'goods');
		if (empty($data)) {
			$seller_goods_category = RC_Model::model('goods/seller_goods_category_model');
			$category_view = RC_Model::model('goods/seller_goods_category_viewmodel');
			
			$cat_ids = $seller_goods_category->get_seller_goods_cat_ids($options);
			$data = array();
			if (!empty($cat_ids)) {
				foreach ($cat_ids as $key => $val) {
					$goods_options = array('seller_id' => $seller_id, 'keywords' => $keyword, 'sort' => $sort_type);
					if (!empty($val['cat_id'])) {
						$goods_options['seller_goods_cat_id'] = $val['cat_id'];
					}
					$data[] = RC_Api::api('goods', 'goods_list', $goods_options);
				}
			}
			RC_Cache::app_cache_set($cache_key, $data, 'goods');
		}
		
		if (!empty($data)) {
			return $data;
		} else {
			return array();
		}
	}	
}

// end