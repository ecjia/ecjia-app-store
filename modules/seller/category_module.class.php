<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺街分类
 * @author 
 *
 */
class category_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	
    	$this->authSession();
		$location = $this->requestData('location');
		RC_Loader::load_app_class('store_category', 'seller');
		//$db_shop = RC_Loader::load_app_model('seller_shopinfo_model', 'seller');
		$scs_view = RC_Model::model('seller/seller_category_shopinfo_viewmodel');
		/* 根据经纬度查询附近店铺*/
// 		if (is_array($location) && isset($location['latitude']) && isset($location['longitude'])) {
// 			$request = array('location' => $location);
// 			$geohash = RC_Loader::load_app_class('geohash', 'shipping');
// 			$where_geohash = $geohash->encode($location['latitude'] , $location['longitude']);
// 			$where_geohash = substr($where_geohash, 0, 5);
					
// 			$where['geohash'] = array('like' => "%$where_geohash%");
// 		}

		$shop_cat = $scs_view->where(array('s.cat_id' => array('gt' => 0), 'is_show' => 1))->group('s.cat_id')->get_field('s.cat_id', true);
		$where['c.cat_id'] = $shop_cat;

		$cat_all = store_category::get_categories_tree($where);
		$cat_all = array_merge($cat_all);
		
		if (!empty($cat_all)) {
			foreach($cat_all as $key => $val) {
				$categoryStore[$key]['id'] = $val['id'];
				$categoryStore[$key]['name'] = $val['name'];
				if (!empty($val['cat_id'])) {
					foreach($val['cat_id'] as $k => $v ) {
						$categoryStore[$key]['children'][$k] = array(
								'id'     => $v['id'],
								'name'   => $v['name'],
						);
							
						if( !empty($v['cat_id']) ) {
							foreach($v['cat_id'] as $k1 => $v1) {
								$categoryStore[$key]['children'][$k1]['children'][] = array(
										'id'     => $v1['id'],
										'name'   => $v1['name'],
								);
							}
						} else {
							$categoryStore[$key]['children'][$k]['children'] = array();
						}
							
						$categoryStore[$key]['children'] = array_merge($categoryStore[$key]['children']);
					}
				} else {
					$categoryStore[$key]['children'] = array();
				}
			}
		}
		return $categoryStore;
	}	
		
}




// end