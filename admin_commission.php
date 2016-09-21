<?php
/**
 * 商家店铺后台设置
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_commission extends ecjia_admin {
	private $admin_user_db; 
	private $msdb;
	private $mspdb;
	private $oi_viewdb;
	private $goods_db;
	private $oidb;
	private $ms_viewdb;
	private $user_db;
	private $mpdb;
	
	public function __construct() {
		parent::__construct();
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		
		//$this->admin_user_db = RC_Loader::load_model('admin_user_model');
		$this->admin_user_db = RC_Model::model('admin_user_model');
		$this->msdb = RC_Loader::load_app_model('merchants_server_model','seller');
		$this->oi_viewdb = RC_Loader::load_app_model('order_info_viewmodel','seller');
		$this->goods_db = RC_Loader::load_app_model('goods_model','seller');
		$this->oidb = RC_Loader::load_app_model('order_info_model','seller'); 
		$this->mspdb = RC_Loader::load_app_model('merchants_steps_process_model','seller');
		$this->ms_viewdb = RC_Loader::load_app_model('merchants_server_viewmodel','seller');
		$this->user_db = RC_Loader::load_app_model('users_model','seller');
		$this->mpdb = RC_Loader::load_app_model('merchants_percent_model','seller');
		
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
		RC_Script::enqueue_script('seller_commission',RC_App::apps_url('statics/js/seller_commission.js' , __FILE__));
		
		RC_Loader::load_app_func('ecmoban', 'seller');
		RC_Loader::load_app_func('order', 'seller');
		RC_Lang::load('merchants_commission');
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金结算'),RC_Uri::url('seller/admin_commission/init')));
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function init() {
		$this->admin_priv('merchants_commission_manage',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金结算')));		
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台佣金结算页面，系统中所有的佣金结算都会显示在此列表中。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金结算" target="_blank">关于佣金结算帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('佣金结算')); 

		$commission_list = $this->merchants_commission_list();
		$this->assign('commission_list',$commission_list);
		
		$this->assign_lang();
		$this->display('merchants_commission_list.dwt');
	}
	
	/**
	 * 设置佣金
	 */
	public function add() {
		$this->admin_priv('merchants_commission_add',ecjia::MSGTYPE_JSON);
	
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
		$this->admin_priv('merchants_commission_add',ecjia::MSGTYPE_JSON);
	
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
			ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'佣金比例是 '.$percent.'%', 'add', 'merchants_commission');
			
			$this->showmessage('设置商家佣金成功！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl'=>RC_Uri::url('seller/admin_commission/edit',array('id'=>$server_id, 'user_id'=>$user_id))));
		} else {
			$this->showmessage('设置商家佣金失败！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function edit()	{
		$this->admin_priv('merchants_commission_update',ecjia::MSGTYPE_JSON);
	
// 		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here( __('编辑商家佣金')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑商家佣金页面，可以在此页面编辑相应的商家佣金。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金结算#.E7.BC.96.E8.BE.91.E4.BD.A3.E9.87.91.E7.BB.93.E7.AE.97" target="_blank">关于编辑商家佣金帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('编辑商家佣金'));
		$this->assign('action_link', array('href' =>RC_Uri::url('seller/admin_commission/init'), 'text' => __('佣金结算')));
		$this->assign('form_action', RC_Uri::url('seller/admin_commission/update'));
		
		$server_id = $_GET['id'];
		$this->assign('server_id',$server_id);
		
		$server = $this->msdb->where(array('server_id' => $server_id))->find();
		$this->assign('server',$server);
		$this->assign('user_id',$server['user_id']);
		
		$user_name = $this->user_db->where(array('user_id'=>$server['user_id']))->get_field('user_name');
		$this->assign('user_name',$user_name);
		
		$date = array('shoprz_brandName, shopNameSuffix');
		$user = get_table_date('merchants_shop_information', "user_id = '$_GET[user_id]'", $date);
		if (empty($user['shoprz_brandName'])) {
			$user['shoprz_brandName'] = '';
		}
		if (empty($user['shopNameSuffix'])) {
			$user['shopNameSuffix'] = '';
		}
		
		$this->assign('user',$user);	//店铺名称
		
		$suppliers_percent = $this->get_suppliers_percent(); //管理员获取佣金百分比
		$this->assign('suppliers_percent', $suppliers_percent);
		
		$this->assign_lang();
		$this->display('merchants_commission_info.dwt');
	}
	
	/**
	 * 订单佣金结算页面
	 */
	public function update() {
		$this->admin_priv('merchants_commission_update',ecjia::MSGTYPE_JSON);
		
		$server_id = $_POST['server_id'];
		$user_id = $_POST['user_id'];
		
		$data = array (
			'user_id'   		=> intval($_POST['user_id']),
			'suppliers_percent' => intval($_POST['suppliers_percent']),
			'suppliers_desc'   	=> trim($_POST['suppliers_desc'])
		);
		
		if (empty($_POST['suppliers_percent'])) {
			$this->showmessage('请选择佣金比例！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		$server_update = $this->msdb->where(array('server_id' => $server_id))->update($data);

		if ($server_update) {
			$user_name = $this->user_db->where(array('user_id' => $user_id))->get_field('user_name');
			$percent = $this->mpdb->where(array('percent_id' => intval($_POST['suppliers_percent'])))->get_field('percent_value');
			ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'佣金比例是 '.$percent.'%', 'edit', 'merchants_commission');
			
			$this->showmessage('编辑成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS , array('pjaxurl' => RC_Uri::url('seller/admin_commission/edit',array('id' => $server_id, 'user_id' => $user_id))));
		} else {
			$this->showmessage('编辑失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	public function remove(){
		$this->admin_priv('merchants_commission_delete',ecjia::MSGTYPE_JSON);
	
		$id = $_GET['id'];
		
		$info = $this->msdb->where(array('server_id'=>$id))->find();
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
			
			$user_name = $this->user_db->where(array('user_id' => $info['user_id']))->get_field('user_name');
			$percent = $this->mpdb->where(array('percent_id' => intval($info['suppliers_percent'])))->get_field('percent_value');
			$this->msdb->where(array('server_id' => $id))->delete();
			ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'佣金比例是 '.$percent.'%', 'remove', 'merchants_commission');
			
			$this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		} else {
			$this->showmessage('删除失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 批量操作
	 */
	public function batch() {
		$this->admin_priv('merchants_commission_delete',ecjia::MSGTYPE_JSON);
		
		$id = $_POST['id'];
		
		$info = $this->ms_viewdb->join(array('users','merchants_percent'))->in(array('server_id' => $id))->field('u.user_name,mp.percent_value')->select();
		
		$server_delete = $this->msdb->in(array('server_id' => $id))->delete();
		
		if ($server_delete) {
			foreach ($info as $v) {
				ecjia_admin::admin_log('商家名是 '.$v['user_name'].'，'.'佣金比例是 '.$v['percent_value'].'%', 'batch_remove', 'merchants_commission');
			}
			
			$this->showmessage('批量删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('seller/admin_commission/init')));
		} else {
			$this->showmessage('批量删除失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	public function order_list() {
		$this->admin_priv('merchants_order_manage',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('商家订单列表')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台商家订单列表页面，可以在此页面查看相信商家的所有佣金结算订单。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金结算#.E8.AE.A2.E5.8D.95.E5.88.97.E8.A1.A8" target="_blank">关于商家订单列表帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('商家订单列表'));
		$this->assign('action_link',array('href' => RC_Uri::url('seller/admin_commission/init'), 'text' =>  __('佣金结算')));
		$this->assign('search_action',RC_Uri::url('seller/admin_commission/order_list',array('id' => $_GET['id'])));
		
		$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$_SESSION['order_userId'] = $user_id;

		$date = array('suppliers_percent');
		$percent_id = get_table_date('merchants_server', "user_id = '$user_id' ", $date, $sqlType = 2);

		$date = array('percent_value');
		$percent_value = get_table_date('merchants_percent', "percent_id = '$percent_id'", $date, $sqlType = 2) . '%';
		$this->assign('percent_value',$percent_value);
		
		$order_list = $this->merchants_order_list($user_id);
		$this->assign('order_list',$order_list);
		
		$this->assign_lang();
		$this->display('merchants_order_list.dwt');
	}
	
	/**
	 * 修改结算状态
	 */
	public function toggle_state()
	{
		$order_id       = intval($_POST['id']);
		$order_sn       = $_GET['order_sn'];
		$arr['is_settlement'] = intval($_POST['val']);
		
		$update = $this->oidb->where(array('order_id'=>$order_id))->update($arr);
		if ($update) {
			$user_name = $this->user_db->where(array('user_id' => $_GET['id']))->get_field('user_name');
			
			if ($arr['is_settlement'] == 1) {
				ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'订单编号是 '.$order_sn.'，'.'已结算', 'setup', 'merchants_commission_stats');
			} else {
				ecjia_admin::admin_log('商家名是 '.$user_name.'，'.'订单编号是 '.$order_sn.'，'.'未结算', 'setup', 'merchants_commission_stats');
			}
			$this->showmessage('结算状态修改成功！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('seller/admin_commission/order_list',array('id' => $_GET['id']))));
		} else {
			$this->showmessage('结算状态修改失败！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
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
	private function merchants_commission_list() {
		$filter['sort_by'] = empty($_GET['sort_by']) ? 's.server_id' : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'ASC' : trim($_GET['sort_order']);
	
		$count = $this->msdb->count();
		$page = new ecjia_page($count,10,5);
		
		$data = $this->ms_viewdb->join(array('merchants_shop_information','users','merchants_steps_fields'))->field('u.user_name, mis.*, msf.*, s.server_id, s.user_id, s.suppliers_desc, s.suppliers_percent')->order(array($filter['sort_by'] => $filter['sort_order']))->group(array('s.user_id'))->limit($page->limit())->select();

		if (!empty($data)) {
			foreach ($data as $key => $val) {
				if (empty($data[$key]['shoprz_brandName'])) {
					$data[$key]['shoprz_brandName'] = '';
				}
				if (empty($data[$key]['shopNameSuffix'])) {
					$data[$key]['shopNameSuffix'] = '';
				}
// 				$valid = $this->get_nerchants_order_valid_refund($val['user_id']); //订单有效总额
				$data[$key]['order_valid_total'] = price_format($valid['total_fee']);
					
				$data[$key]['percent_value'] = $this->mpdb->where(array('percent_id'=>$val['suppliers_percent']))->get_field('percent_value');
					
// 				$refund = $this->get_nerchants_order_valid_refund($val['user_id'], 1); //订单退款总额
				$data[$key]['order_refund_total'] = price_format($refund['total_fee']);
			}
		}
	
		$arr = array('item' => $data, 'filter'=>$filter,'page' => $page->show(5), 'desc' => $page->page_desc(), 'current_page' => $page->current_page);
		return $arr;
	}
	
	//佣金百分比
	private function get_suppliers_percent() {
		$mpdb = RC_Loader::load_app_model('merchants_percent_model','seller');
		$res = $mpdb->field('percent_id, percent_value')->order(array('sort_order' => 'asc'))->select();
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
	private function get_nerchants_order_valid_refund($ru_id, $type = 0) {
		
		$where =array();
		if ($type == 1) {
			$where = array(
				'o.order_status'		=> OS_RETURNED,
				'o.shipping_status'		=> SS_UNSHIPPED,
				'o.pay_status'			=> PS_UNPAYED
			);
		} else {
			$order_query = RC_Loader::load_app_class('order_query', 'seller');
			$where = $order_query->order_finished('o.');
		}
		$total_fee = "SUM(" . order_amount_field('o.', $ru_id) . ") AS total_fee ";
		$order_info = RC_Loader::load_app_model('order_info_viewmodel', 'seller');

		$where = array_merge($where, array('ru_id' => $ru_id, 'oi2.main_order_id is null'));
	
		$res = $order_info->join(array('order_goods', 'order_info'))->field($total_fee)->where($where)->group('o.order_id')->find();
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
	private function merchants_order_list($user_id = 0) {
		
		$filter['sort_by'] = empty($_GET['sort_by']) ? 'o.order_id' : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'DESC' : trim($_GET['sort_order']);
		
		$filter['start_time'] = empty($_GET['start_time']) ? '' : RC_Time::local_strtotime(trim($_GET['start_time']));
		$filter['end_time'] = empty($_GET['end_time']) ? '' : RC_Time::local_strtotime(trim($_GET['end_time']));
		
		$where = '1';
		$count = $this->oidb->field('count(*)')->select();
		$string = $this->oidb->last_sql();
		
		$where .= " AND ($string as oi2 where oi2.main_order_id = o.order_id) = 0 ";
		$where .= " AND o.is_delete = 0 AND og.ru_id = '$user_id' group by o.order_id ";

		if (!empty($filter['start_time'])) {
			$where .= " AND o.add_time > '" .$filter['start_time'] . "'";
		}
		if(!empty($filter['end_time'])){
			$where .= " AND o.add_time < '" .$filter['end_time']. "'";
		}
		
		$res = $this->oi_viewdb->join(array('users','order_goods','goods'))->field('o.order_id')->where($where)->select();
		
		$count = count($res);
		
		$page = new ecjia_page($count,10,5);
		
		$field = "og.ru_id, o.order_id, o.main_order_id, o.order_sn, o.add_time, o.order_status, o.shipping_status, o.order_amount, o.money_paid, o.is_delete, o.is_settlement," . 
				 "o.shipping_time, o.auto_delivery_time, o.pay_status, o.consignee, o.address, o.email, o.tel, o.extension_code, o.extension_id, " .
				 "(" . get_order_amount_field('o.') . ") AS total_fee, " .
				 "IFNULL(u.user_name, '" .RC_Lang::lang('anonymous'). "') AS buyer ";
		
		$row = $this->oi_viewdb->join(array('users','order_goods','goods'))->field($field)->where($where)->order(array($filter['sort_by'] => $filter['sort_order']))->limit($page->limit())->select();
		
		$data_count = count($row);
		
		for ($i=0; $i<$data_count; $i++) {
			$row[$i]['formated_order_amount'] 	= price_format($row[$i]['order_amount']);
			$row[$i]['formated_money_paid'] 	= price_format($row[$i]['money_paid']);
			$row[$i]['formated_total_fee'] 		= price_format($row[$i]['total_fee']);
			$row[$i]['short_order_time'] 		= RC_Time::local_date('Y-m-d H:i', $row[$i]['add_time']);
			
			$date = array('suppliers_percent');
			$percent_id = get_table_date('merchants_server', "user_id = '" .$row[$i]['ru_id']. "' ", $date, $sqlType = 2);
			
			$date = array('percent_value');
			$percent_value = get_table_date('merchants_percent', "percent_id = '$percent_id'", $date, $sqlType = 2);
			
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