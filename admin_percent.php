<?php
/**
 * 商家佣金设置
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_percent extends ecjia_admin {
	private $mp_db;
	public function __construct() {
		parent::__construct();
		$this->mp_db = RC_Loader::load_app_model('merchants_percent_model','seller');
		
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		
		RC_Loader::load_app_func('ecmoban', 'seller');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('bootstrap-placeholder');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Style::enqueue_style('chosen');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Style::enqueue_style('uniform-aristo');
		
		RC_Script::enqueue_script('bootstrap-editable-script', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Style::enqueue_style('bootstrap-editable-css', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		
		RC_Lang::load('merchants_percent');
		RC_Script::enqueue_script('seller_commission', RC_App::apps_url('statics/js/seller_commission.js', __FILE__));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金比例'),RC_Uri::url('seller/admin_percent/init')));
	}
	
	/**
	 * 商家佣金列表
	 */
	public function init() {
		$this->admin_priv('merchants_percent_manage',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('佣金比例')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台佣金比例页面，系统中所有的佣金比例都会显示在此列表上。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金比例" target="_blank">关于佣金比例帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('佣金比例')); // 当前导航				
		$this->assign('add_percent' , array('href' => RC_Uri::url('seller/admin_percent/add'), 'text' => __('添加佣金比例')));
		
		$percent_list = $this->get_percent_list();
		$this->assign('percent_list',$percent_list);
		
		/* 显示模板 */
		$this->assign_lang();
		$this->display('merchants_percent_list.dwt');
	}
	
	/**
	 * 添加佣金百分比页面
	 */
	public function add() {
		$this->admin_priv('merchants_percent_add',ecjia::MSGTYPE_JSON);

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加佣金比例')));		
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台添加佣金比例页面，可以在此页面添加佣金比例。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金比例#.E6.B7.BB.E5.8A.A0.E4.BD.A3.E9.87.91.E6.AF.94.E4.BE.8B" target="_blank">关于添加佣金比例帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('添加佣金比例'));
		$this->assign('action_link', array('href' =>RC_Uri::url('seller/admin_percent/init'), 'text' => __('佣金比例')));
		$this->assign('form_action', RC_Uri::url('seller/admin_percent/insert'));

		$this->assign_lang();
		$this->display('merchants_percent_info.dwt');
	}
	
	/**
	 * 添加佣金百分比加载
	 */
	public function insert() {
		$this->admin_priv('merchants_percent_add',ecjia::MSGTYPE_JSON);
		
		if (empty($_POST['percent_value'])) {
			$this->showmessage('奖励额度不能为空',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		if (!is_numeric($_POST['percent_value'])) {
			$this->showmessage('奖励额度必须为数字',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$data = array(
			'percent_value'	=> trim($_POST['percent_value']),
			'sort_order'	=> trim($_POST['sort_order']),
			'add_time'		=> RC_Time::gmtime()
		);
		
		$result = $this->mp_db->where(array('percent_value' => $data['percent_value']))->select();
		
		if (!empty($result)) {
			$this->showmessage('该奖励额度已存在！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
			
		$percent_id = $this->mp_db->insert($data);
		
		if ($percent_id) {
			ecjia_admin::admin_log($_POST['percent_value'].'%', 'add', 'merchants_percent');
			$links = array(
				array('href' => RC_Uri::url('seller/admin_percent/init'), 'text' => __('返回佣金比例列表')),
				array('href' => RC_Uri::url('seller/admin_percent/add'), 'text' => __('继续添加佣金比例')),
			);
			$this->showmessage('添加佣金比例成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS , array('links' => $links , 'pjaxurl' => RC_Uri::url('seller/admin_percent/edit',array('id' => $percent_id))));	
		} else {
			$this->showmessage('添加佣金比例失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 佣金百分比编辑页面
	 */
	public function edit() {		
		$this->admin_priv('merchants_percent_update',ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑佣金比例')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑佣金比例页面，可以在此页面编辑相应佣金比例信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:佣金比例#.E7.BC.96.E8.BE.91.E4.BD.A3.E9.87.91.E6.AF.94.E4.BE.8B" target="_blank">关于编辑佣金比例帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', __('编辑佣金比例'));
		$this->assign('action_link', array('href' =>RC_Uri::url('seller/admin_percent/init'), 'text' => __('佣金比例')));
		$this->assign('form_action', RC_Uri::url('seller/admin_percent/update'));
		
		/* 取得奖励额度信息 */
		$id = $_GET['id'];
		$this->assign('id',$id);
		
		$percent = $this->mp_db->where(array('percent_id'=>$id))->find();

		if ($percent['add_time']) {
			$percent['add_time'] = RC_Time::local_strtotime($percent['add_time']);
		}
		$this->assign('percent',$percent);
		
		$this->assign_lang();
		$this->display('merchants_percent_info.dwt');
	}
	
	/**
	 * 佣金百分比 编辑 加载
	 */
	public function update() {
		$this->admin_priv('merchants_percent_update',ecjia::MSGTYPE_JSON);
		
		$percent_id = $_POST['id'];
		$data = array( 
			'percent_value' => trim($_POST['percent_value']),
			'sort_order' => trim($_POST['sort_order'])
		);
		
		/* 取得奖励额度信息 */
		$percent_info = $this->mp_db->where(array('percent_id'=>$percent_id))->find();
		
		/* 判断名称是否重复 */
		$is_only = $this->mp_db->where(array('percent_value' => $data['percent_value'], 'percent_id' => array('neq' => $percent_id)))->find();
		
		if (!empty($is_only)) {
			$this->showmessage('该奖励额度已存在！',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		/* 保存奖励额度信息 */
		$percent_update = $this->mp_db->where(array('percent_id' => $percent_id))->update($data);
		
		/* 提示信息 */
		if ($percent_update) {
			ecjia_admin::admin_log($_POST['percent_value'].'%', 'edit', 'merchants_percent');
			$this->showmessage('编辑成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS , array('pjaxurl' => RC_Uri::url('seller/admin_percent/edit',array('id' => $percent_id))));
		} else {
			$this->showmessage('编辑失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	//删除佣金百分比
	public function remove() {
		$this->admin_priv('merchants_percent_delete',ecjia::MSGTYPE_JSON);
		
		$id = $_GET['id'];
		$percent_value = $this->mp_db->where(array('percent_id'=>$id))->get_field('percent_value');
		$percent_delete = $this->mp_db->where(array('percent_id'=>$id))->delete();
		
		if ($percent_delete) {
			ecjia_admin::admin_log($percent_value.'%', 'remove', 'merchants_percent');
			$this->showmessage('删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		} else {
			$this->showmessage('删除失败',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	//批量删除佣金百分比
	public function batch() {
		$this->admin_priv('merchants_percent_delete',ecjia::MSGTYPE_JSON);
		
		/* 对批量操作进行权限检查  BY：MaLiuWei  END */
		$id = $_POST['id'];
		$info = $this->mp_db->in(array('percent_id' => $id))->field('percent_value')->select();

		$percent_delete = $this->mp_db->in(array('percent_id' => $id))->delete();
		
		if ($percent_delete) {
			foreach ($info as $v) {
				ecjia_admin::admin_log($v['percent_value'].'%', 'batch_remove', 'merchants_percent');
			}
			$this->showmessage('批量删除成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('seller/admin_percent/init')));
		} else {
			$this->showmessage('批量删除失败',ecjia::MSGTYPE_HMTL | ecjia::MSGSTAT_ERROR);
		}
	}
	
	//获取佣金百分比列表
	private function get_percent_list() {
		$filter['sort_by']    = empty($_GET['sort_by']) ? 'sort_order' : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'asc' : trim($_GET['sort_order']);
		
		$count = $this->mp_db->count();
		$page = new ecjia_page($count,20,5);
		
		$data = $this->mp_db->order(array($filter['sort_by'] => $filter['sort_order']))->limit($page->limit())->select();
		if (!empty($data)) {
			foreach ($data as $k => $v) {
				$data[$k]['add_time'] = RC_Time::local_date('Y-m-d',$v['add_time']);
			}
		}
		
		return array('item' => $data, 'filter'=>$filter, 'page' => $page->show(5), 'desc' => $page->page_desc(), 'current_page' => $page->current_page);
	}
}
//end