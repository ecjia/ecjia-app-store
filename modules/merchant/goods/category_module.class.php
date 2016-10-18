<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商铺商品分类
 * @author will.chen
 *
 */
class category_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
    	
    	$this->authSession();	
		$seller_id = $this->requestData('seller_id');//后期删除
		//$store_id = $this->requestData('store_id');//新增
		
		$mc_db = RC_Model::model('seller/merchants_category_model');
		$category_dbview = RC_Model::model('seller/category_viewmodel');
		
		$cat_id = $mc_db->where(array('seller_id' => $seller_id))->get_field('cat_id', true);//后期删除
		//$cat_id = $mc_db->where(array('store_id' => $store_id))->get_field('cat_id', true);//新增

		$cat_id = !empty($cat_id) ? $cat_id : 0;
		$field = 'c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(cc.cat_id) AS has_children';
		$cat_list = $category_dbview->join(array('category'))
								->field($field)
								->in(array('c.cat_id' => $cat_id))
								->where(array('c.is_show' => 1))
								->group('c.cat_id')
								->order(array('c.parent_id' => 'asc', 'c.sort_order' => asc))
								->select();
		
		$options = cat_options(0, $cat_list);
		$cat_lists = array();
		if (!empty($options)) {
			$last_cat_id = $first_cat_id = 0;
			foreach ($options as $key => $value) {
				if ($value['level'] > 2) {
					continue;
				}
				if ($value['level'] == 0) {
					$cat_lists[$key] = array(
						'id' => $value['cat_id'],
						'name' => $value['cat_name'],
						'children' => array()
					);
					if ($first_cat_id !=0) {
						$cat_lists[$first_cat_id]['children'] = array_merge($cat_lists[$first_cat_id]['children']);
					}
					$first_cat_id = $value['cat_id'];
				}
				
				if ($value['level'] == 1) {
					$cat_lists[$value['parent_id']]['children'][$key] = array(
							'id' => $value['cat_id'],
							'name' => $value['cat_name'],
							'children' => array()
					);
					
				}
				
				if ($value['level'] == 2) {
					$cat_lists[$first_cat_id]['children'][$value['parent_id']]['children'][] = array(
							'id' => $value['cat_id'],
							'name' => $value['cat_name'],
					);
					
				}
				$last_cat_id = $value['cat_id'];
			}
			$cat_lists[$first_cat_id]['children'] = array_merge($cat_lists[$first_cat_id]['children']);
		}
		$cat_lists = array_merge($cat_lists);
		return $cat_lists;
	}	
}

/**
 * 过滤和排序所有分类，返回一个带有缩进级别的数组
 *
 * @access private
 * @param int $cat_id
 *        	上级分类ID
 * @param array $arr
 *        	含有所有分类的数组
 * @param int $level
 *        	级别
 * @return void
 */
function cat_options($spec_cat_id, $arr) {
	$level = $last_cat_id = 0;
	$options = $cat_id_array = $level_array = array ();

	while ( ! empty ( $arr ) ) {
		foreach ( $arr as $key => $value ) {
			$cat_id = $value ['cat_id'];
			if ($level == 0 && $last_cat_id == 0) {
				$options [$cat_id] = $value;
				$options [$cat_id] ['level'] = $level;
				$options [$cat_id] ['id'] = $cat_id;
				$options [$cat_id] ['name'] = $value ['cat_name'];
				unset ( $arr [$key] );

				if ($value ['has_children'] == 0) {
					continue;
				}
				$last_cat_id = $cat_id;
				$cat_id_array = array (
						$cat_id
				);
				$level_array [$last_cat_id] = ++ $level;

				continue;
			}
				
			if ($value ['parent_id'] == $last_cat_id) {
				$options [$cat_id] = $value;
				$options [$cat_id] ['level'] = $level;
				$options [$cat_id] ['id'] = $cat_id;
				$options [$cat_id] ['name'] = $value ['cat_name'];
				unset ( $arr [$key] );

				if ($value ['has_children'] > 0) {
					if (end ( $cat_id_array ) != $last_cat_id) {
						$cat_id_array [] = $last_cat_id;
					}
					$last_cat_id = $cat_id;
					$cat_id_array [] = $cat_id;
					$level_array [$last_cat_id] = ++ $level;
				}
			} elseif ($value ['parent_id'] > $last_cat_id) {
				break;
			}
		}

		$count = count ( $cat_id_array );
		if ($count > 1) {
			$last_cat_id = array_pop ( $cat_id_array );
		} elseif ($count == 1) {
			if ($last_cat_id != end ( $cat_id_array )) {
				$last_cat_id = end ( $cat_id_array );
			} else {
				$level = 0;
				$last_cat_id = 0;
				$cat_id_array = array ();
				continue;
			}
		}

		if ($last_cat_id && isset ( $level_array [$last_cat_id] )) {
			$level = $level_array [$last_cat_id];
		} else {
			$level = 0;
		}
	}
	return $options;
}

// end