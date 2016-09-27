<?php
/**
 * 商家店铺后台设置
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_commission extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		
		/* 加载全局 js/css */		
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('bootstrap-editable',RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('bootstrap-placeholder');
		RC_Script::enqueue_script('bootstrap-editable.min',RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		
		RC_Script::enqueue_script('media-editor',RC_Uri::vendor_url('tinymce/tinymce.min.js'));
		RC_Script::enqueue_script('commission',RC_App::apps_url('statics/js/commission.js' , __FILE__));
		
		RC_Loader::load_app_func('ecmoban', 'store');
		RC_Loader::load_app_func('order', 'store');
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金结算'),RC_Uri::url('store/admin_commission/init')));
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function init() {
		$this->admin_priv('store_commission_manage',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金结算')));		
		
		$this->assign('ur_here', __('佣金结算')); 

		$commission_list = $this->store_commission_list();
		$this->assign('commission_list',$commission_list);
		
		$this->display('store_commission_list.dwt');
	}
	
	/**
	 * 设置佣金
	 */
	public function add() {
		$this->admin_priv('store_commission_add',ecjia::MSGTYPE_JSON);
	
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here( __('设置商家佣金')));

		$this->assign('ur_here', __('设置商家佣金'));
		$this->assign('action_link'  , array('href' => RC_Uri::url('seller/admin_commission/init'), 'text' => __('佣金结算')));
		$this->assign('form_action',RC_Uri::url('seller/admin_commission/insert'));
	
		$suppliers_percent = $this->get_suppliers_percent();
		$this->assign('suppliers_percent', $suppliers_percent);
	
		$date = array('shoprz_brandName, shopNameSuffix');
		$user = get_table_date('merchants_shop_information', "user_id = '$_GET[user_id]'", $date);
		if (empty($user['shoprz_brandName'])) {
			$user['shoprz_brandName'] = '';
		}
		if (empty($user['shopNameSuffix'])) {
			$user['shopNameSuffix'] = '';
		}
	
		$user_name = $this->user_db->where(array('user_id'=>$_GET['user_id']))->get_field('user_name');
		$this->assign('user_name',$user_name);
		$this->assign('user', $user);
		$this->assign('user_id', $_GET[user_id]);
	
		$this->assign_lang();
		$this->display('merchants_commission_info.dwt');
	}
	
	public function insert() {
		$this->admin_priv('store_commission_add',ecjia::MSGTYPE_JSON);
	
		$user_id			= isset($_POST['user_id']) 		? intval($_POST['user_id']) 		: 0;
		$suppliers_percent	= isset($_POST['suppliers_percent']) 	? intval($_POST['suppliers_percent']) 	: 0;
		$suppliers_desc		= isset($_POST['suppliers_desc']) 		? trim($_POST['suppliers_desc']) 		: '';
	
		if (empty($_POST['suppliers_percent'])) {
			$this->showmessage('请选择佣金比例！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$data = array (
			'user_id'			=> $user_id,
			'suppliers_desc'	=> $suppliers_desc,
			'suppliers_percent'	=> $suppliers_percent
		);

		$server_id = $this->msdb->insert($data);
	
		if ($server_id) {
			$user_name = $this->user_db->where(array('user_id' => $user_id))->get_field('user_name');
			$percent = $this->mpdb->where(array('percent_id' => $suppliers_percent))->get_field('percent_value');
			ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'佣金比例是 '.$percent.'%', 'add', 'store_commission');
			
			$this->showmessage('设置商家佣金成功！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl'=>RC_Uri::url('seller/admin_commission/edit',array('id'=>$server_id, 'user_id'=>$user_id))));
		} else {
			$this->showmessage('设置商家佣金失败！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function edit()	{
		$this->admin_priv('store_commission_update',ecjia::MSGTYPE_JSON);
	
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here( __('编辑商家佣金')));
		$this->assign('ur_here', __('编辑商家佣金'));
		$this->assign('action_link', array('href' =>RC_Uri::url('store/admin_commission/init'), 'text' => __('佣金结算')));
		$this->assign('form_action', RC_Uri::url('store/admin_commission/update'));
		
		$id = $_GET['id'];
		$this->assign('id',$id);
		
		//$store_commission = $this->msdb->where(array('server_id' => $server_id))->find();
		$store_commission = RC_DB::table('store_commission')->where(RC_DB::raw('id'), '=', $id)->first();
		$this->assign('store_commission',$store_commission);
		
		//$merchants_name = $this->user_db->where(array('user_id'=>$server['user_id']))->get_field('user_name');
		$merchants_name = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), '=', $store_commission['store_id'])->pluck('merchants_name');
		$this->assign('merchants_name',$merchants_name);
		$this->assign('store_id', $store_commission['store_id']);
		
		$store_percent = $this->get_suppliers_percent(); //管理员获取佣金百分比
		
		$this->assign('store_percent', $store_percent);
		
		$this->display('store_commission_info.dwt');
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function update() {
		$this->admin_priv('store_commission_update',ecjia::MSGTYPE_JSON);
		
		$id = $_POST['id'];
		$store_id = $_POST['store_id'];
		
		$data = array (
			'percent_id' => intval($_POST['percent_id']),
			'description'   	=> trim($_POST['description'])
		);
		
		if (empty($_POST['percent_id'])) {
			$this->showmessage('请选择佣金比例！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		RC_DB::table('store_commission')
				->where(RC_DB::raw('id'), '=', $id)
				->where(RC_DB::raw('store_id'), '=', $store_id)->update($data);

			$merchants_name = RC_DB::table('store_franchisee')
								->where(RC_DB::raw('store_id'), '=', $store_id)->pluck('merchants_name');

			$percent = RC_DB::table('store_percent')->where(RC_DB::raw('percent_id'), '=', $_POST['percent_id'])->pluck('percent_value');
			ecjia_admin::admin_log('商家名是 '.$merchants_name.'，'.'佣金比例是 '.$percent.'%', 'edit', 'store_commission');
			
			$this->showmessage('编辑成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS , array('pjaxurl' => RC_Uri::url('store/admin_commission/edit',array('id' => $id, 'store_id' => $store_id))));
	}

	public function remove(){
		$this->admin_priv('store_commission_delete',ecjia::MSGTYPE_JSON);
	
		$id = $_GET['id'];		
		//$info = $this->msdb->where(array('server_id'=>$id))->find();
		$info = RC_DB::table('store_commission')->where(RC_DB::raw('id'), '=', $id)->first();
		$srore_id = $info['store_id'];
		if ($info) {
			//判断是否存在订单
// 			$order_list = $this->merchants_order_list($info['user_id']);
// 			$order_exists = $this->oi_viewdb->join(array('order_goods','goods'))->where(array('server_id'=>$id))->count();
			
			//判断是否存在商品
// 			$goods_exists = $this->goods_db->where(array('goods_id'=>$id))->count();

			/* 删除管理员、发货单关联、退货单关联和订单关联的服务站 */
// 			$table_array = array($this->db_admin_user,$this->db_delivery_order,$this->db_back_order);
			
// 			foreach ($table_array as $value) {
// 				$value->where(array('server_id'=>$id))->delete();
// 			}
			
			//$user_name = $this->user_db->where(array('user_id' => $info['user_id']))->get_field('user_name');
			//$percent = $this->mpdb->where(array('percent_id' => intval($info['suppliers_percent'])))->get_field('percent_value');
			//$this->msdb->where(array('server_id' => $id))->delete();
			$merchants_name = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), $srore_id)->pluck('merchants_name');
			$percent = RC_DB::table('store_percent')->where(RC_DB::raw('percent_id'), '=', intval($info['percent_id']))->pluck('percent_value');
			
			RC_DB::table('store_commission')->where(RC_DB::raw('id'), $id)->delete();
			
			ecjia_admin::admin_log('商家名是 '.$merchants_name.'，'.'佣金比例是 '.$percent.'%', 'remove', 'store_commission');
			
			$this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		} else {
			$this->showmessage('删除失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 批量操作
	 */
	public function batch() {
		$this->admin_priv('store_commission_delete',ecjia::MSGTYPE_JSON);
		
		$id = $_POST['id'];
		$id_new = explode(',', $id);
		
		//$info = $this->ms_viewdb->join(array('users','merchants_percent'))->in(array('server_id' => $id))->field('u.user_name,mp.percent_value')->select();
		$info = RC_DB::table('store_commission as sc')
				 ->leftJoin('store_franchisee as sf', RC_DB::raw('sc.store_id'), '=', RC_DB::raw('sf.store_id'))
				 ->leftJoin('store_percent as sp', RC_DB::raw('sc.percent_id'), '=', RC_DB::raw('sp.percent_id'))
				 ->selectRaw('sf.merchants_name, sp.percent_value')->get();
		//$server_delete = $this->msdb->in(array('server_id' => $id))->delete();
		
		$server_delete = RC_DB::table('store_commission')->whereIn(RC_DB::raw('id'), $id_new)->delete();
		
		if ($server_delete) {
			foreach ($info as $k => $v) {
				ecjia_admin::admin_log('商家名是 '.$v['merchants_name'].'，'.'佣金比例是 '.$v['percent_value'].'%', 'batch_remove', 'store_commission');
			}
			
			$this->showmessage('批量删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_commission/init')));
		} else {
			$this->showmessage('批量删除失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	public function order_list() {
		$this->admin_priv('store_order_manage',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('商家订单列表')));
// 		ecjia_screen::get_current_screen()->add_help_tab(array(
// 			'id'		=> 'overview',
// 			'title'		=> __('概述'),
// 			'content'	=>
// 			'<p>' . __('欢迎访问ECJia智能后台商家订单列表页面，可以在此页面查看相信商家的所有佣金结算订单。') . '</p>'
// 		));
		
// 		ecjia_screen::get_current_screen()->set_help_sidebar(
// 			'<p><strong>' . __('更多信息:') . '</strong></p>' .
// 			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金结算#.E8.AE.A2.E5.8D.95.E5.88.97.E8.A1.A8" target="_blank">关于商家订单列表帮助文档</a>') . '</p>'
// 		);
		
		$this->assign('ur_here', __('商家订单列表'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin_commission/init'), 'text' =>  __('佣金结算')));
		$this->assign('search_action',RC_Uri::url('store/admin_commission/order_list',array('store_id' => $_GET['store_id'])));
		
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		//$_SESSION['order_userId'] = $user_id;
		//$date = array('percent_id');
		//$percent_id = get_table_date('store_commission', "store_id = '$store_id' ", $date, $sqlType = 2);
		//$date = array('percent_value');
		//$percent_value = get_table_date('store_commission', "percent_id = '$percent_id'", $date, $sqlType = 2) . '%';
		$percent_id = RC_DB::table('store_commission')
						->where(RC_DB::raw('store_id'), $store_id)
						->pluck(RC_DB::raw('percent_id'));
		$percent_value = RC_DB::table('store_percent')
						   ->where(RC_DB::raw('percent_id'), $percent_id)
						   ->pluck(RC_DB::raw('percent_value'));
		$percent_value = $percent_value.'%';
		$this->assign('percent_value',$percent_value);
		
		$order_list = $this->store_order_list($store_id);
		$this->assign('order_list',$order_list);
	
		$this->display('store_order_list.dwt');
	}
	
	/**
	 * 修改结算状态
	 */
	public function toggle_state()
	{
		$order_id       = intval($_POST['id']);
		$order_sn       = $_GET['order_sn'];
		$arr['is_settlement'] = intval($_POST['val']);
		
		//$update = $this->oidb->where(array('order_id'=>$order_id))->update($arr);
		RC_DB::table('order_info')->where(RC_DB::raw('order_id'), $order_id)->update($arr);
// 		if ($update) {
			//$user_name = $this->user_db->where(array('user_id' => $_GET['id']))->get_field('user_name');
			$merchants_name = RC_DB::table('store_franchisee')->where(RC_DB::raw('store_id'), $_GET['id'])->pluck(RC_DB::raw('merchants_name'));
			if ($arr['is_settlement'] == 1) {
				ecjia_admin::admin_log('商家名是 '.$merchants_name.'，'.'订单编号是 '.$order_sn.'，'.'已结算', 'setup', 'store_commission_status');
			} else {
				ecjia_admin::admin_log('商家名是 '.$merchants_name.'，'.'订单编号是 '.$order_sn.'，'.'未结算', 'setup', 'store_commission_status');
			}
			$this->showmessage('结算状态修改成功！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_commission/order_list',array('id' => $_GET['id']))));
// 		} else {
// 			$this->showmessage('结算状态修改失败！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
// 		}
	}
	
	public function get_shop_name() {
		$filter = $_GET['JSON'];
		$filter = (object)$filter;
		
		$user_id = $filter->user_id;
		$date = array('shoprz_brandName, shopNameSuffix');
		$user = get_table_date('merchants_shop_information', "user_id = '$user_id'", $date);
		if (empty($user['shoprz_brandName'])) {
			$user['shoprz_brandName'] = '';
		}
		if (empty($user['shopNameSuffix'])) {
			$user['shopNameSuffix'] = '';
		}
		$user['user_id'] = $user_id;
		$opt = array(
			'value' => $user['shoprz_brandName'],
			'text'  => $user['shopNameSuffix']
		);
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $opt));
	}

	/**
	 *  获取商家佣金列表
	 */
	private function store_commission_list() {
		$filter['sort_by'] = empty($_GET['sort_by']) ?  RC_DB::raw('sc.id') : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'ASC' : trim($_GET['sort_order']);
	
		//$count = $this->msdb->count();
		$count = RC_DB::table('store_commission')->count();
		$page = new ecjia_page($count,10,5);
		
		//$data = $this->ms_viewdb->join(array('merchants_shop_information','users','merchants_steps_fields'))
		//->field('u.user_name, mis.*, msf.*, s.server_id, s.user_id, s.suppliers_desc, s.suppliers_percent')
		//->order(array($filter['sort_by'] => $filter['sort_order']))->group(array('s.user_id'))->limit($page->limit())->select();
		
		$dbview = RC_DB::table('store_commission as sc')
				 ->leftJoin('store_franchisee as sf', RC_DB::raw('sc.store_id'), '=', RC_DB::raw('sf.store_id'))
				 ->leftJoin('store_percent as sp', RC_DB::raw('sc.percent_id'), '=', RC_DB::raw('sp.percent_id'));
		
		$data =  $dbview
				 ->selectRaw('sf.merchants_name, sf.contact_mobile, sf.store_id, sc.percent_id, sp.percent_value, sc.id')
				 ->orderBy($filter['sort_by'], $filter['sort_order'])
				 ->groupBy(RC_DB::raw('sc.store_id'))->take(10)->skip($page->start_id-1)->get();
	
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				//$valid = $this->get_nerchants_order_valid_refund($val['user_id']); //订单有效总额
				//$valid = $this->get_nerchants_order_valid_refund($val['store_id']);//订单有效总额
				$data[$key]['order_valid_total'] = price_format($valid['total_fee']);
					
				//$data[$key]['percent_value'] = $this->mpdb->where(array('percent_id'=>$val['suppliers_percent']))->get_field('percent_value');
					
				//$refund = $this->get_nerchants_order_valid_refund($val['user_id'], 1); //订单退款总额
				$data[$key]['order_refund_total'] = price_format($refund['total_fee']);
			}
		}
	
		$arr = array('item' => $data, 'filter'=>$filter,'page' => $page->show(5), 'desc' => $page->page_desc(), 'current_page' => $page->current_page);
		return $arr;
	}
	
	//佣金百分比
	private function get_suppliers_percent() {
		$res = RC_DB::table('store_percent')
				 ->select(RC_DB::raw('percent_id'), RC_DB::raw('percent_value'))
				 ->orderBy('sort_order', 'asc')->get();
		
		return $res;
	}
	//获取列表商家
	private function get_merchants_user_list() {
		$msidb = RC_Loader::load_app_model('merchants_shop_information_model','seller');
		$res = $msidb->select();
		$arr = array();
		if (!empty($res)) {
			foreach ($res as $key=>$row) {
				$arr[$key] = $row;
				$data = array('user_name');
				$user_name = get_table_date('users', "user_id = '" .$row['user_id']. "'", $data, 2);
				$arr[$key]['user_name'] = $user_name;
			}
		}
		return $arr;
	}	
	
	//商家订单有效金额和退款金额
	private function get_nerchants_order_valid_refund($store_id, $type = 0) {
		
		$where =array();
		if ($type == 1) {
			//$where = array(
			//	'o.order_status'		=> OS_RETURNED,
			//	'o.shipping_status'		=> SS_UNSHIPPED,
			//	'o.pay_status'			=> PS_UNPAYED
			//);
			$where = '';
			$where = "o.order_status = ".OS_RETURNED."" ."and o.shipping_status = ".SS_UNSHIPPED."" ."o.pay_status = ".PS_UNPAYED."";
		} else {
			$order_query = RC_Loader::load_app_class('order_query', 'store');
			$where = $order_query->order_finished('o.');
		}
		$total_fee = "SUM(" . order_amount_field('o.', $store_id) . ") AS total_fee ";
		//$order_info = RC_Loader::load_app_model('order_info_viewmodel', 'seller');
		$order_info = RC_DB::table('order_info as o')
						->leftJoin('order_goods as og', RC_DB::raw('o.order_id'), '=', RC_DB::table('og.order_id'));
		//$where = array_merge($where, array('ru_id' => $ru_id, 'oi2.main_order_id is null'));
		//$res = $order_info->join(array('order_goods', 'order_info'))->field($total_fee)->where($where)->group('o.order_id')->find();
		
		//$res = $order_info->selectRaw($total_fee)->where($where)->groupBy(RC_DB::raw('o.order_id'))->first();
// 		_dump($order_info->toSql(), 1);
		return $res;
	}
	
	/**
	 * 生成查询订单的sql
	 * @param   string  $type   类型
	 * @param   string  $alias  order表的别名（包括.例如 o.）
	 * @return  string
	 */
	private function order_query_sql($type = 'finished', $alias = '') {
		/* 已完成订单 */
		if ($type == 'finished') {
			return " AND {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
			" AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
			" AND {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " ";
		}
		/* 待发货订单 */
		elseif ($type == 'await_ship') {
			return " AND   {$alias}order_status " .
			db_create_in(array(OS_CONFIRMED, OS_SPLITED, OS_SPLITING_PART)) .
			" AND   {$alias}shipping_status " .
			db_create_in(array(SS_UNSHIPPED, SS_PREPARING, SS_SHIPPED_ING)) .
			" AND ( {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " OR {$alias}pay_id " . db_create_in(payment_id_list(true)) . ") ";
		}
		/* 待付款订单 */
		elseif ($type == 'await_pay') {
			return " AND   {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
			" AND   {$alias}pay_status = '" . PS_UNPAYED . "'" .
			" AND ( {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . " OR {$alias}pay_id " . db_create_in(payment_id_list(false)) . ") ";
		}
		/* 未确认订单 */
		elseif ($type == 'unconfirmed') {
			return " AND {$alias}order_status = '" . OS_UNCONFIRMED . "' ";
		}
		/* 未处理订单：用户可操作 */
		elseif ($type == 'unprocessed') {
			return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
			" AND {$alias}shipping_status = '" . SS_UNSHIPPED . "'" .
			" AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
		}
		/* 未付款未发货订单：管理员可操作 */
		elseif ($type == 'unpay_unship') {
			return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
			" AND {$alias}shipping_status " . db_create_in(array(SS_UNSHIPPED, SS_PREPARING)) .
			" AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
		}
		/* 已发货订单：不论是否付款 */
		elseif ($type == 'shipped') {
			return " AND {$alias}order_status = '" . OS_CONFIRMED . "'" .
			" AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . " ";
		}
		else {
			die('函数 order_query_sql 参数错误');
		}
	}

	/**
	 * 创建像这样的查询: "IN('a','b')";
	 *
	 * @access public
	 * @param mix $item_list
	 *        	列表数组或字符串
	 * @param string $field_name
	 *        	字段名称
	 *
	 * @return void
	 */
	private function db_create_in($item_list, $field_name = '') {
		if (empty ( $item_list )) {
			return $field_name . " IN ('') ";
		} else {
			if (! is_array ( $item_list )) {
				$item_list = explode ( ',', $item_list );
			}
			$item_list = array_unique ( $item_list );
			$item_list_tmp = '';
			foreach ( $item_list as $item ) {
				if ($item !== '') {
					$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
				}
			}
			if (empty ( $item_list_tmp )) {
				return $field_name . " IN ('') ";
			} else {
				return $field_name . ' IN (' . $item_list_tmp . ') ';
			}
		}
	}
	
	/**
	 *  获取商家订单列表
	 */
	private function store_order_list($store_id = 0) {
		
		$filter['sort_by'] = empty($_GET['sort_by']) ? RC_DB::raw('o.order_id') : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'DESC' : trim($_GET['sort_order']);
		
		$filter['start_time'] = empty($_GET['start_time']) ? '' : RC_Time::local_strtotime(trim($_GET['start_time']));
		$filter['end_time'] = empty($_GET['end_time']) ? '' : RC_Time::local_strtotime(trim($_GET['end_time']));
		
		$where = '1';
		//$count = $this->oidb->field('count(*)')->select();
		//$string = $this->oidb->last_sql();
		$count = RC_DB::table('order_info')->select(RC_DB::raw('count(*)'))->get();
		$string = RC_DB::table('order_info')->toSql();
		
		//$where .= " AND ($string as oi2 where oi2.main_order_id = o.order_id) = 0 ";
		//$where .= " AND o.is_delete = 0 AND o.store_id = '$store_id' group by o.order_id ";
		
		$where .= " AND o.is_delete = 0 AND o.store_id = '$store_id' ";
		if (!empty($filter['start_time'])) {
			$where .= " AND o.add_time > '" .$filter['start_time'] . "'";
		}
		if(!empty($filter['end_time'])){
			$where .= " AND o.add_time < '" .$filter['end_time']. "'";
		}
		
		//$res = $this->oi_viewdb->join(array('users','order_goods','goods'))->field('o.order_id')->where($where)->select();
		$dbview = RC_DB::table('order_info as o')
				->leftJoin('users as u', RC_DB::raw('u.user_id'), '=', RC_DB::raw('o.user_id'))
				->leftJoin('order_goods as og', RC_DB::raw('o.order_id'), '=', RC_DB::raw('og.order_id'))
				->leftJoin('goods as g', RC_DB::raw('g.goods_id'), '=', RC_DB::raw('og.goods_id'));
		
		$res	=  $dbview->selectRaw('o.order_id')->whereRaw($where)->groupBy(RC_DB::raw('o.order_id'))->get();
		
		$count = count($res);
		
		$page = new ecjia_page($count,10,5);
		
		//$field = "o.store_id, o.order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement," . 
		//		 "o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id, " .
		//		 "(" . get_order_amount_field('o.') . ") AS total_fee, " .
		//		 "IFNULL(u.user_name, '" . RC_Lang::get('store::store.anonymous'). "') AS buyer ";
		
		$field1 = "o.store_id, o.order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement";
		$field2 = "o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id";
		$field3 = "(". get_order_amount_field('o.') .") AS buyer ";
		$field4 = " IFNULL(u.user_name, '" . RC_Lang::get('store::store.anonymous'). "') AS buyer ";
		
		//$row = $this->oi_viewdb->join(array('users','order_goods','goods'))->field($field)->where($where)->order(array($filter['sort_by'] => $filter['sort_order']))->limit($page->limit())->select();

		$row = $dbview
				 ->select(RC_DB::raw($field1), RC_DB::raw($field2), RC_DB::raw($field3), RC_DB::raw($field4))
				 ->whereRaw($where)
				 ->orderBy($filter['sort_by'], $filter['sort_order'])
				 ->groupBy(RC_DB::raw('o.order_id'))
				 ->take(10)
				 ->skip($page->start_id-1)
				 ->get();
		
		$data_count = count($row);
		
		for ($i=0; $i<$data_count; $i++) {
			$row[$i]['formated_order_amount'] 	= price_format($row[$i]['order_amount']);
			$row[$i]['formated_money_paid'] 	= price_format($row[$i]['money_paid']);
			$row[$i]['formated_total_fee'] 		= price_format($row[$i]['total_fee']);
			$row[$i]['short_order_time'] 		= RC_Time::local_date('Y-m-d H:i', $row[$i]['add_time']);
			
			//$date = array('suppliers_percent');
			//$percent_id = get_table_date('merchants_server', "user_id = '" .$row[$i]['ru_id']. "' ", $date, $sqlType = 2);
			//$date = array('percent_value');
			//$percent_value = get_table_date('merchants_percent', "percent_id = '$percent_id'", $date, $sqlType = 2);
			
			$percent_id = RC_DB::table('store_commission')
							->where(RC_DB::raw('store_id'), $row[$i]['store_id'])->pluck(RC_DB::raw('percent_id'));
			$percent_value = RC_DB::table('store_percent')
							   ->where(RC_DB::raw('percent_id'), $percent_id)->pluck(RC_DB::raw('percent_value'));
			
			if ($percent_value == 0) {
				$percent_value = 1;
			} else {
				$percent_value = $percent_value/100;
			}
			
			$row[$i]['formated_brokerage_amount'] = price_format($row[$i]['total_fee'] * $percent_value);

			$filter['all_brokerage_amount'] += $row[$i]['total_fee'] * $percent_value;
		}
		if (!empty($filter['all_brokerage_amount'])) {
			$filter['all_brokerage_amount'] = price_format($filter['all_brokerage_amount']);
		}
    	$arr = array('item' => $row, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'current_page' => $page->current_page);
    	return $arr;
	}
}

//end