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
		/* 根据经纬度查询附近店铺*/
		if (is_array($filter['location']) && !empty($filter['location']['latitude']) && !empty($filter['location']['longitude'])) {
			$geohash = RC_Loader::load_app_class('geohash', 'store');
			$geohash_code = $geohash->encode($filter['location']['latitude'] , $filter['location']['longitude']);
			$geohash_code = substr($geohash_code, 0, 5);
			$where['geohash'] = array('like' => "%$geohash_code%");
		}

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
			$seller_group = RC_Model::model('goods/goods_model')
									->where($seller_group_where)
									->get_field('store_id', true);

			if (!empty($seller_group)) {
				$where['sf.store_id'] = array_unique($seller_group);
			} else {
				$where['sf.store_id'] = 0;
			}
		}
		$where['ssi.status'] = 1;

		if (!empty($filter['keywords'])) {
			$where['merchants_name'] = array('like' => "%".$filter['keywords']."%");
		}

		// /* 店铺分类*/
		if (!empty($filter['category_id'])) {
			RC_Loader::load_app_func('store_category','seller');
			$where['ssi.cat_id'] = get_children($filter['category_id']);
		}


// 		$msi_dbview = RC_Loader::load_app_model('merchants_shop_information_viewmodel', 'seller');
		// $ssi_dbview = RC_Model::model('seller/seller_shopinfo_viewmodel');

        $db_store_franchisee = RC_Model::model('store/store_franchisee_viewmodel');
		$count = $db_store_franchisee->join(null)->where($where)->count();

		//加载分页类
		RC_Loader::load_sys_class('ecjia_page', false);
		//实例化分页
		$page_row = new ecjia_page($count, $filter['size'], 6, '', $filter['page']);

		$user_id = $_SESSION['user_id'];

		$limit = $filter['limit'] == 'all' ? null : $page_row->limit();

// 		$field ='ssi.*, ssi.id as seller_id, ssi.shop_name as seller_name, sc.cat_name, count(cs.seller_id) as follower, SUM(IF(cs.user_id = '.$user_id.',1,0)) as is_follower';
// 		$result = $ssi_dbview->join(array('seller_category', 'collect_store'))
// 								->field($field)
// 								->where($where)
// 								->limit($limit)
// 								->group('ssi.id')
// // 								->order($order_by)
// 								->select();
//
// 		$seller_list = array();

        $field = 'ssi.*, sc.cat_name, count(ssi.store_id) as follower, SUM(IF(cs.user_id = '.$user_id.',1,0)) as is_follower';
        $result = $db_store_franchisee->join(array('collect_store', 'store_category'))->field($field)->where($where)->limit($limit)->group('ssi.store_id')->order($order_by)->select();
        foreach($result as $k => $val){
            $store_config = array(
                'shop_title'                => '', // 店铺标题
                'shop_kf_mobile'            => '', // 客服手机号码
                'shop_kf_email'             => '', // 客服邮件地址
                'shop_kf_qq'                => '', // 客服QQ号码
                'shop_kf_ww'                => '', // 客服淘宝旺旺
                'shop_kf_type'              => '', // 客服样式
                'shop_logo'                 => '', // 默认店铺页头部LOGO
                'shop_thumb_logo'           => '', // Logo缩略图
                'shop_banner_pic'           => '', // banner图
                'shop_qrcode_logo'          => '', // 二维码中间Logo
                'shop_trade_time'           => '', // 营业时间
                'shop_description'          => '', // 店铺描述
                'shop_notice'               => '', // 店铺公告
                'shop_front_logo'           => '', // 店铺封面图
            );
            $config = RC_DB::table('merchants_config')->where('store_id', $val['store_id'])->select('code','value')->get();
            foreach ($config as $key => $value) {
                $store_config[$value['code']] = $value['value'];
            }
            $result[$k] = array_merge($result[$k], $store_config);
        }

		if (!empty ($result)) {
			foreach ($result as $key => $val) {

				if(substr($val['shop_logo'], 0, 1) == '.') {
					$val['shop_logo'] = str_replace('../', '/', $val['shop_logo']);
				}

				$seller_list[] = array(
						'id'				 => $val['store_id'],
						'merchants_name'	 => $val['merchants_name'],
						'shop_cat_name'	     => $val['cat_name'],
						'shop_logo'		     => empty($val['shop_logo']) ?  '' : RC_Upload::upload_url($val['shop_logo']),
						'follower'			 => $val['follower'],
						'is_follower'		 => $val['is_follower'],
				);
			}
		}

		return array('seller_list' => $seller_list, 'page' => $page_row);
	}
}


// end
