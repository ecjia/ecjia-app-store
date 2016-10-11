<?php
/**
 * 入驻商家待审核列表
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_preaudit extends ecjia_admin {
	
	public function __construct() {
		parent::__construct();
		
		//全局JS和CSS
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-placeholder');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('bootstrap-editable.min',RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		RC_Script::enqueue_script('jquery-uniform');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Style::enqueue_style('chosen');
		
		RC_Script::enqueue_script('store', RC_App::apps_url('statics/js/store.js', __FILE__));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.store_preaudit'), RC_Uri::url('store/admin_preaudit/init')));
	}
	
	/**
	 * 入驻商家预审核列表
	 */
	public function init() {
	    $this->admin_priv('store_preaudit_manage',ecjia::MSGTYPE_JSON);
		
	    $this->assign('ur_here', RC_Lang::get('store::store.store_preaudit_list'));
	    
	    $store_list = $this->store_preaudit_list();
	    $this->assign('store_list', $store_list);
	    
	    $this->assign('search_action', RC_Uri::url('store/admin_preaudit/init'));
	    
	    $this->display('store_preaudit_list.dwt');
	}
	
	/**
	 * 编辑入驻商
	 */
	public function edit() {
		$this->admin_priv('store_preaudit_update', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here',RC_Lang::get('store::store.store_update'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin_preaudit/init'),'text' => RC_Lang::get('store::store.store_preaudit')));
		
		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_preaudit')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$this->assign('store', $store);
		$cat_list = $this->get_cat_select_list();
		$this->assign('cat_list', $cat_list);
		
		$this->assign('form_action',RC_Uri::url('store/admin_preaudit/update'));

		$this->display('store_preaudit_edit.dwt');
	}
	
	/**
	 * 编辑入驻商数据更新
	 */
	public function update() {
		$this->admin_priv('store_preaudit_update', ecjia::MSGTYPE_JSON);
		
		$store_id = intval($_POST['store_id']);
		
		$pic_url = RC_DB::table('store_preaudit')->where('store_id', $store_id)->first();
		
		if (!empty($_FILES['one']['name'])) {
			$upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
			$info = $upload->upload($_FILES['one']);
			if (!empty($info)) {
				$business_licence_pic = $upload->get_position($info);
				$upload->remove($pic_url['business_licence_pic']);
			} else {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}else {
			$business_licence_pic = $pic_url['business_licence_pic'];
		}
		
		if (!empty($_FILES['two']['name'])) {
			$upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
			$info = $upload->upload($_FILES['two']);
			if (!empty($info)) {
				$identity_pic_front = $upload->get_position($info);
				$upload->remove($pic_url['identity_pic_front']);
			} else {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}else {
			$identity_pic_front = $pic_url['identity_pic_front'];
		}

		if (!empty($_FILES['three']['name'])) {
			$upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
			$info = $upload->upload($_FILES['three']);
			if (!empty($info)) {
				$identity_pic_back = $upload->get_position($info);
				$upload->remove($pic_url['identity_pic_back']);
			} else {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}else {
			$identity_pic_back = $pic_url['identity_pic_back'];
		}
		
		if (!empty($_FILES['four']['name'])) {
			$upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
			$info = $upload->upload($_FILES['four']);
			if (!empty($info)) {
				$personhand_identity_pic = $upload->get_position($info);
				$upload->remove($pic_url['personhand_identity_pic']);
			} else {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}else {
			$personhand_identity_pic = $pic_url['personhand_identity_pic'];
		}
		
		$data = array(
			'cat_id'   	   				=> !empty($_POST['store_cat']) 		? $_POST['store_cat'] : '',
			'merchants_name'   			=> !empty($_POST['merchants_name']) ? $_POST['merchants_name'] : '',
			'shop_keyword'      		=> !empty($_POST['shop_keyword']) 	? $_POST['shop_keyword'] : '',
			'responsible_person'		=> !empty($_POST['responsible_person']) ? $_POST['responsible_person'] : '',
			'company_name'      		=> !empty($_POST['company_name']) 		? $_POST['company_name'] : '',
			'email'      				=> !empty($_POST['email']) 				? $_POST['email'] : '',
			'contact_mobile'    		=> !empty($_POST['contact_mobile']) 	? $_POST['contact_mobile'] : '',
			'address'      				=> !empty($_POST['address']) 			? $_POST['address'] : '',
			'identity_type'     		=> !empty($_POST['identity_type']) 		? $_POST['identity_type'] : '',
			'identity_number'   		=> !empty($_POST['identity_number']) 	? $_POST['identity_number'] : '',
			'identity_pic_front'		=> $identity_pic_front,
			'identity_pic_back' 		=> $identity_pic_back,
			'personhand_identity_pic'	=>$personhand_identity_pic,
			'business_licence'  		=> !empty($_POST['business_licence']) 	? $_POST['business_licence'] : '',
			'business_licence_pic' 		=> $business_licence_pic,
			'bank_name'      	   		=> !empty($_POST['bank_name']) 				? $_POST['bank_name'] : '',
			'bank_branch_name'     		=> !empty($_POST['bank_branch_name']) 		? $_POST['bank_branch_name'] : '',
			'bank_account_number' 	 	=> !empty($_POST['bank_account_number'])		? $_POST['bank_account_number'] : '',
			'bank_address'         		=> !empty($_POST['bank_address']) 			? $_POST['bank_address'] : '',
		);
	
		RC_DB::table('store_preaudit')->where('store_id', $store_id)->update($data);

		$this->showmessage(RC_Lang::get('store::store.edit_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_preaudit/edit', array('store_id' => $store_id))));
	}
	
	/**
	 * 审核入驻商
	 */
	public function check() {
		$this->admin_priv('store_preaudit_check', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here',RC_Lang::get('store::store.check_view'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin_preaudit/init'),'text' => RC_Lang::get('store::store.store_preaudit')));
		
		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_preaudit')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$this->assign('store', $store);
		
		$this->assign('form_action',RC_Uri::url('store/admin_preaudit/check_update'));
	
		$this->display('store_preaudit_check.dwt');
	}
	
	
	/**
	 * 审核入驻商
	 */
	public function check_update() {
		$this->admin_priv('store_preaudit_update', ecjia::MSGTYPE_JSON);
		
		$store_id    = intval($_POST['store_id']);
		$remark 	=!empty($_POST['remark']) ? $_POST['remark'] : '';
		$original 	=!empty($_POST['original']) ? $_POST['original'] : '';
		$check_log =array(
			'store_id' 		=> 	$store_id,
// 			'original' 		=> 	$original,
// 			'new' 			=> 	$new,
			'info'			=>	$remark,
			'time'			=>	RC_Time::gmtime(),
		);
		RC_DB::table('store_check_log')->insertGetId($check_log);
		if($_POST['check_status'] == 2) {//通过
			$store = RC_DB::table('store_preaudit')->where('store_id', $store_id)->first();
			$data =array(
				'store_id' 		=> $store_id,
				'cat_id' 		=> $store['cat_id'],
				'merchants_name'=> $store['merchants_name'],
				'shop_keyword'	=> $store['shop_keyword'],
				'status'		=> 1,
				'responsible_person'		=>$store['responsible_person'],
				'company_name'				=>$store['company_name'],
				'email'						=>$store['email'],
				'contact_mobile'			=>$store['contact_mobile'],
				'apply_time'				=>$store['apply_time'],
				'confirm_time'				=>RC_Time::gmtime(),
				'address'					=>$store['address'],
				'identity_type'				=>$store['identity_type'],
				'identity_number'			=>$store['identity_number'],
				'identity_pic_front'		=>$store['identity_pic_front'],
				'identity_pic_back'			=>$store['identity_pic_back'],
				'personhand_identity_pic'	=>$store['personhand_identity_pic'],
				'business_licence'			=>$store['business_licence'],
				'business_licence_pic'		=>$store['business_licence_pic'],
				'bank_name'					=>$store['bank_name'],
				'bank_branch_name'			=>$store['bank_branch_name'],
				'bank_account_number'		=>$store['bank_account_number'],
				'bank_address'				=>$store['bank_address'],
				'remark'					=>$remark,
				'longitude'					=>$store['longitude'],
				'latitude'					=>$store['latitude'],
				'sort_order' 				=> 50,
			);
			$merchant_config = array(
// 	            'shop_kf_mobile',         // 客服手机号码
// 	            'shop_kf_email' ,         // 客服邮件地址
// 	            'shop_kf_qq'    ,         // 客服QQ号码
// 	            'shop_kf_ww'    ,         // 客服淘宝旺旺
// 	            'shop_kf_online_ident',   // 在线客服账号
// 	            'shop_kf_appkey',         // 在线客服appkey
// 	            'shop_kf_secretkey',      // 在线客服secretkey
// 	            'shop_kf_welcomeMsg',     // 在线客服欢迎信息
// 	            'shop_kf_type',           // 客服样式
// 	            'shop_trade_time',        // 营业时间
// 	            'shop_description',       // 店铺描述
// 	            'shop_notice',            // 店铺公告
// 	            'shop_logo',              // 默认店铺页头部LOGO
// 	            'shop_kf_logo',           // 在线客服头像LOGO
// 	            'shop_thumb_logo',        // Logo缩略图
// 	            'shop_banner_pic',		  // banner图
// 	            'shop_qrcode_logo',       // 二维码中间Logo
// 	            'shop_front_logo',		  //店铺封面图
	            
				'shop_title' ,                // 店铺标题
				'shop_kf_mobile' ,            // 客服手机号码
				'shop_kf_email' ,             // 客服邮件地址
				'shop_kf_type' ,              // 客服样式
				'shop_kf_qq'  ,               // 客服QQ号码
				'shop_kf_ww' ,                // 客服淘宝旺旺
				'shop_logo' ,                 // 默认店铺页头部LOGO
				'shop_front_logo',            // 店铺封面图
				'shop_thumb_logo' ,           // Logo缩略图
				'shop_banner_pic' ,           // banner图
				'shop_qrcode_logo' ,          // 二维码中间Logo
				'shop_trade_time' ,           // 营业时间
				'shop_description' ,          // 店铺描述
				'shop_notice'   ,             // 店铺公告
       		 );
			$merchants_config = RC_DB::table('merchants_config');
			foreach ($merchant_config as $val) {
				$count = $merchants_config->where(RC_DB::raw('store_id'), $store_id)->where(RC_DB::raw('code'), $val)->count();
				if(empty($count)){
					$merchants_config->insert(array('store_id' => $store_id, 'code' => $val));
				}
			}
			RC_DB::table('store_franchisee')->insert($data);
			RC_DB::table('store_preaudit')->where('store_id', $store_id)->delete();

			
			$salt = rand(1, 9999);
			//审核通过产生一个主员工的资料
			$data = array(
				'mobile' 		=> $store['contact_mobile'],
				'store_id' 		=> $store_id,
				'name' 			=> $store['responsible_person'],
				'nick_name' 	=> '',
				'user_ident' 	=> '',
				'email' 		=> $store['email'],
				'password' 		=> md5(md5('123456') . $salt),
				'salt'			=> $salt,
				'add_time' 		=> RC_Time::gmtime(),
				'last_login' 	=> '',
				'last_ip' 		=> '',
				'action_list' 	=> '',
				'todolist' 		=> '',
				'group_id' 		=> '',
				'parent_id' 	=> 0,
				'avatar' 		=> '',
			);
			RC_DB::table('staff_user')->insertGetId($data);
			
			$this->showmessage(RC_Lang::get('store::store.check_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_preaudit/init', array('store_id' => $store_id))));

		}else {
			RC_DB::table('store_preaudit')->where('store_id', $store_id)->update(array('remark'=>$remark));
			$this->showmessage(RC_Lang::get('store::store.deal_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin_preaudit/check', array('store_id' => $store_id))));
		}
	}
	
	//获取入驻商列表信息
	private function store_preaudit_list() {
		$db_store_franchisee = RC_DB::table('store_preaudit as sp');
		
		$filter['keywords'] = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		if ($filter['keywords']) {
			$db_store_franchisee->where('merchants_name', 'like', '%'.mysql_like_quote($filter['keywords']).'%');
		}
		
		$count = $db_store_franchisee->count();
		$page = new ecjia_page($count, 10, 5);
		$data = $db_store_franchisee
		->leftJoin('store_category as sc', RC_DB::raw('sp.cat_id'), '=', RC_DB::raw('sc.cat_id'))
		->selectRaw('sp.store_id,sp.merchants_name,sp.merchants_name,sp.responsible_person,sp.apply_time,sp.company_name,sc.cat_name')
		->orderby('store_id', 'asc')
		->take(10)
		->get();
		$res = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['apply_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['apply_time']);
				$res[] = $row;
			}
		}
		return array('store_list' => $res, 'filter' => $filter, 'page' => $page->show(5), 'desc' => $page->page_desc());
	}
	
	
	/**
	 * 获取店铺分类表
	 */
	private function get_cat_select_list() {
		$data = RC_DB::table('store_category')
			->select('cat_id', 'cat_name')
			->orderBy('cat_id', 'desc')
			->get();
		$cat_list = array();
		if (!empty($data)) {
			foreach ($data as $row ) {
				$cat_list[$row['cat_id']] = $row['cat_name'];
			}
		}
		return $cat_list;
	}
}

//end