<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 店铺列表接口
 * @author
 *
 */
class store_store_list_api extends Component_Event_Api {
	/**
	 *
	 * @param array $options
	 * @return  array
	 */
	public function call (&$options) {
        if (!is_array($options)) {
			return new ecjia_error('invalid_parameter', RC_Lang::get('system::system.invalid_parameter'));
		}

		return $this->store_list($options);
	}

    /**
	 *  获取店铺列表
	 *
	 * @access  private
	 * @return  array       $order_list     订单列表
	 */
	private function store_list($filter)
	{
        $where = array();
        $where['ssi.status'] = 1;
        $where['ssi.store_id'] = array();
		/* 商品分类*/
		if (!empty($filter['goods_category'])) {
			RC_Loader::load_app_class('goods_category', 'goods', false);
			isset($filter['goods_category']) and $children = goods_category::get_children($filter['goods_category'], 'cat_id');
			$seller_group_where = array(
					"(". $children ." OR ".goods_category::get_extension_goods($children, 'goods_id').")",
					'is_on_sale'	=> 1,
					'is_alone_sale' => 1,
					'is_delete'		=> 0,
			);
			if (ecjia::config('review_goods')) {
				$seller_group_where['review_status'] = array('gt' => 2);
			}
			$seller_group = RC_Model::model('goods/goods_viewmodel')->join(null)
									->where($seller_group_where)
									->get_field('store_id', true);
			
			if (!empty($seller_group)) {
				$where['ssi.store_id'] = $seller_group = array_unique($seller_group);
			}
		}
		
		if (isset($seller_group) && !empty($seller_group) && !empty($filter['store_id_group'])) {
			$where['ssi.store_id'] = array_intersect($seller_group, $filter['store_id_group']);
		} elseif (!empty($filter['store_id_group'])) {
			$where['ssi.store_id'] = $filter['store_id_group'];
		}

		if (!empty($filter['keywords'])) {
			$where[] = '(merchants_name like "%'.$filter['keywords'].'%" or goods_name like "%'.$filter['keywords'].'%")';
		}

		// /* 店铺分类*/
		if (!empty($filter['seller_category'])) {
// 			RC_Loader::load_app_func('store_category','store');
// 			$where['ssi.cat_id'] = get_children($filter['category_id']);
			$where['ssi.cat_id'] = $filter['category_id'];
		}

        $db_store_franchisee = RC_Model::model('store/store_franchisee_viewmodel');
		$count = $db_store_franchisee->join(array('goods'))->where($where)->count('distinct(ssi.store_id)');

		//加载分页类
		RC_Loader::load_sys_class('ecjia_page', false);
		//实例化分页
		$page_row = new ecjia_page($count, $filter['size'], 6, '', $filter['page']);

		$user_id = $_SESSION['user_id'];

		$limit = $filter['limit'] == 'all' ? null : $page_row->limit();

		$seller_list = array();

        $field = 'ssi.*, sc.cat_name, count(cs.store_id) as follower';
        $result = $db_store_franchisee->join(array('collect_store', 'store_category', 'goods'))->field($field)->where($where)->limit($limit)->group('ssi.store_id')->order($filter['sort'])->select();
        if (!empty($result)) {
        	foreach($result as $k => $val){
        		$store_config = array(
        				'shop_kf_mobile'            => '', // 客服手机号码
//         				'shop_kf_email'             => '', // 客服邮件地址
//         				'shop_kf_qq'                => '', // 客服QQ号码
//         				'shop_kf_ww'                => '', // 客服淘宝旺旺
//         				'shop_kf_type'              => '', // 客服样式
        				'shop_logo'                 => '', // 默认店铺页头部LOGO
        				'shop_banner_pic'           => '', // banner图
        				'shop_trade_time'           => '', // 营业时间
        				'shop_description'          => '', // 店铺描述
        				'shop_notice'               => '', // 店铺公告
        		);
        		$config = RC_DB::table('merchants_config')->where('store_id', $val['store_id'])->select('code','value')->get();
        		foreach ($config as $key => $value) {
        			$store_config[$value['code']] = $value['value'];
        		}
        		$result[$k] = array_merge($result[$k], $store_config);
        	
        		if(substr($result[$k]['shop_logo'], 0, 1) == '.') {
        			$result[$k]['shop_logo'] = str_replace('../', '/', $val['shop_logo']);
        		}
        	
        		$seller_list[] = array(
        				'id'				 => $result[$k]['store_id'],
        				'seller_name'		 => $result[$k]['merchants_name'],
        				'seller_category'	 => $result[$k]['cat_name'],//后期删除
        				'manage_mode'		 => $result[$k]['manage_mode'],
        				'shop_logo'		     => empty($result[$k]['shop_logo']) ?  '' : RC_Upload::upload_url($result[$k]['shop_logo']),//后期增加
        				'seller_logo'		 => empty($result[$k]['shop_logo']) ?  '' : RC_Upload::upload_url($result[$k]['shop_logo']),//后期删除
        				'follower'			 => $result[$k]['follower'],
            		    'location' => array(
            		        'latitude'  => $result[$k]['latitude'],
            		        'longitude' => $result[$k]['longitude'],
            		    ),
        		);
        	}
        }
        
		return array('seller_list' => $seller_list, 'page' => $page_row);
	}
}


// end
