<?php
/**
 * 入驻商家待审核列表
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_advance extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('chosen');

		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('smoke');

		RC_Script::enqueue_script('jquery.toggle.buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/jquery.toggle.buttons.js'));
		RC_Style::enqueue_style('bootstrap-toggle-buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/bootstrap-toggle-buttons.css'));
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		RC_Script::enqueue_script('bootstrap-placeholder');
		
		RC_Script::enqueue_script('store', RC_App::apps_url('statics/js/store.js', __FILE__));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.store_advance'), RC_Uri::url('store/admin_advance/init')));
	}
	
	/**
	 * 入驻商家预审核列表
	 */
	public function init() {
	    $this->admin_priv('store_advance_manage',ecjia::MSGTYPE_JSON);
		
	    $this->assign('ur_here', RC_Lang::get('store::store.store_advance_list'));
	    
	    $store_list = $this->store_advance_list();
	    $this->assign('store_list', $store_list);
	    
	    $this->assign('search_action', RC_Uri::url('store/admin_advance/init'));
	    
	    $this->display('store_advance_list.dwt');
	}
	
	/**
	 * 编辑入驻商
	 */
	public function edit() {
		$this->admin_priv('store_advance_update', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here',RC_Lang::get('store::store.store_update'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin_advance/init'),'text' => RC_Lang::get('store::store.store_advance')));
		
		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_advance')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$this->assign('store', $store);
		
		$this->assign('form_action',RC_Uri::url('store/admin_advance/update'));

		$this->display('store_advance_edit.dwt');
	}
	
	/**
	 * 编辑入驻商数据更新
	 */
	public function update() {
		$this->admin_priv('store_advance_update', ecjia::MSGTYPE_JSON);
		$store_id = intval($_POST['store_id']);
		$data = array(
			'cat_id'   	   		=> !empty($_POST['cat_id']) 		? $_POST['cat_id'] : '',
			'merchants_name'   	=> !empty($_POST['merchants_name']) ? $_POST['merchants_name'] : '',
			'shop_keyword'      => !empty($_POST['shop_keyword']) 	? $_POST['shop_keyword'] : '',
			'responsible_person'=> !empty($_POST['responsible_person']) ? $_POST['responsible_person'] : '',
			'company_name'      => !empty($_POST['company_name']) 		? $_POST['company_name'] : '',
			'email'      		=> !empty($_POST['email']) 				? $_POST['email'] : '',
			'contact_mobile'    => !empty($_POST['contact_mobile']) 	? $_POST['contact_mobile'] : '',
			'address'      		=> !empty($_POST['address']) 			? $_POST['address'] : '',
			'identity_type'     => !empty($_POST['identity_type']) 		? $_POST['identity_type'] : '',
			'identity_number'   => !empty($_POST['identity_number']) 	? $_POST['identity_number'] : '',
			'identity_pic_front'=> !empty($_POST['identity_pic_front']) ? $_POST['identity_pic_front'] : '',
			'identity_pic_back' => !empty($_POST['identity_pic_back']) 	? $_POST['identity_pic_back'] : '',
			'business_licence'  => !empty($_POST['business_licence']) 	? $_POST['business_licence'] : '',
			'business_licence_pic' => !empty($_POST['business_licence_pic']) 	? $_POST['business_licence_pic'] : '',
			'bank_name'      	   => !empty($_POST['bank_name']) 				? $_POST['bank_name'] : '',
			'bank_account_number'  => !empty($_POST['bank_account_number'])		? $_POST['bank_account_number'] : '',
			'bank_address'         => !empty($_POST['bank_address']) 			? $_POST['bank_address'] : '',
		);
	
		RC_DB::table('store_advance')->where('store_id', $store_id)->update($data);

		$this->showmessage(RC_Lang::get('store::store.edit_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_advance/edit', array('store_id' => $store_id))));
	}
	
	/**
	 * 审核入驻商
	 */
	public function check() {
		$this->admin_priv('store_advance_check', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here',RC_Lang::get('store::store.check_view'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin_advance/init'),'text' => RC_Lang::get('store::store.store_advance')));
		
		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_advance')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$this->assign('store', $store);
		
		$this->assign('form_action',RC_Uri::url('store/admin_advance/check_update'));
	
		$this->display('store_advance_check.dwt');
	}
	
	
	/**
	 * 审核入驻商
	 */
	public function check_update() {
		$this->admin_priv('store_advance_update', ecjia::MSGTYPE_JSON);
		
		$store_id    = intval($_POST['store_id']);
		$remark 	=!empty($_POST['remark']) ? $_POST['remark'] : '';
		$original 	=!empty($_POST['original']) ? $_POST['original'] : '';
		$check_log =array(
			'store_id' 		=> 	$store_id,
			'original' 		=> 	$original,
			'info'			=>	$remark,
			'time'			=>	RC_Time::gmtime(),
		);
		RC_DB::table('check_log')->insertGetId($check_log);
		if($_POST['check'] == 2) {//通过
			$store = RC_DB::table('store_advance')->where('store_id', $store_id)->first();
			$data =array(
					'store_id' 		=> $store_id,
					'cat_id' 		=> $store['cat_id'],
					'merchants_name'=>$store['merchants_name'],
					'shop_keyword'	=>$store['shop_keyword'],
					'responsible_person'	=>$store['responsible_person'],
					'company_name'			=>$store['company_name'],
					'email'					=>$store['email'],
					'contact_mobile'		=>$store['contact_mobile'],
					'apply_time'			=>$store['apply_time'],
					'confirm_time'			=>RC_Time::gmtime(),
					'address'				=>$store['address'],
					'identity_type'			=>$store['identity_type'],
					'identity_number'		=>$store['identity_number'],
					'identity_pic_front'	=>$store['identity_pic_front'],
					'identity_pic_back'		=>$store['identity_pic_back'],
					'business_licence'		=>$store['business_licence'],
					'business_licence_pic'	=>$store['business_licence_pic'],
					'bank_name'				=>$store['bank_name'],
					'bank_account_number'	=>$store['bank_account_number'],
					'bank_address'			=>$store['bank_address'],
					'longitude'				=>$store['longitude'],
					'latitude'				=>$store['latitude'],
					'sort_order' 			=> 50,
					'remark'				=>$remark,
			);
			RC_DB::table('store_franchisee')->insert($data);
			RC_DB::table('store_advance')->where('store_id', $store_id)->delete();
			$this->showmessage(RC_Lang::get('store::store.check_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_advance/init', array('store_id' => $store_id))));
		}else {
			RC_DB::table('store_advance')->where('store_id', $store_id)->update(array('remark'=>$remark));
			$this->showmessage(RC_Lang::get('store::store.deal_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_advance/check', array('store_id' => $store_id))));
		}
	}
	
	//获取入驻商列表信息
	private function store_advance_list() {
		$db_store_franchisee = RC_DB::table('store_advance as sa');
		
		$filter['keywords'] = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		if ($filter['keywords']) {
			$db_store_franchisee->where('merchants_name', 'like', '%'.mysql_like_quote($filter['keywords']).'%');
		}
		
		$count = $db_store_franchisee->count();
		$page = new ecjia_page($count, 10, 5);
		$data = $db_store_franchisee
		->leftJoin('store_category as sc', RC_DB::raw('sa.cat_id'), '=', RC_DB::raw('sc.cat_id'))
		->selectRaw('sa.store_id,sa.merchants_name,sc.cat_name')
		->orderby('store_id', 'asc')
		->take(10)
		->get();
		$res = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['start_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['start_time']);
				$row['end_time']   = RC_Time::local_date(ecjia::config('time_format'), $row['end_time']);
				$res[] = $row;
			}
		}
		return array('store_list' => $res, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc());
	}
}

//end