<?php
/**
 * 入驻商家管理
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin extends ecjia_admin {

	public function __construct() {
		parent::__construct();

		RC_Loader::load_app_func('global');
		assign_adminlog_content();

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

		RC_Style::enqueue_style('splashy');

		RC_Script::enqueue_script('store', RC_App::apps_url('statics/js/store.js', __FILE__));
		RC_Script::enqueue_script('commission_info',RC_App::apps_url('statics/js/commission.js' , __FILE__));

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.store'), RC_Uri::url('store/admin/init')));
	}

	/**
	 * 入驻商家列表
	 */
	public function init() {
	    $this->admin_priv('store_affiliate_manage',ecjia::MSGTYPE_JSON);

	    ecjia_screen::get_current_screen()->remove_last_nav_here();
	    ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('入驻商'));

	    $this->assign('ur_here', RC_Lang::get('store::store.store_list'));

	    $store_list = $this->store_list();
	    $this->assign('store_list', $store_list);
	    $this->assign('filter', $store_list['filter']);

	    $this->assign('search_action',RC_Uri::url('store/admin/init'));

	    $this->display('store_list.dwt');
	}

	/**
	 * 编辑入驻商
	 */
	public function edit() {
		$this->admin_priv('store_affiliate_update', ecjia::MSGTYPE_JSON);

		$this->assign('ur_here',RC_Lang::get('store::store.store_update'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('编辑入驻商'));

		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$store['confirm_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['confirm_time']);
		$this->assign('store', $store);
		$cat_list = $this->get_cat_select_list();
		$this->assign('cat_list', $cat_list);

		$this->assign('form_action',RC_Uri::url('store/admin/update'));
		$this->assign('longitudeForm_action',RC_Uri::url('store/admin/get_longitude'));

		$this->display('store_edit.dwt');
	}

	/**
	 * 编辑入驻商数据更新
	 */
	public function update() {
		$this->admin_priv('store_affiliate_update', ecjia::MSGTYPE_JSON);

		$store_id = intval($_POST['store_id']);

		$pic_url = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();

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
			'personhand_identity_pic' 	=> $personhand_identity_pic,
			'business_licence'  		=> !empty($_POST['business_licence']) 	? $_POST['business_licence'] : '',
			'business_licence_pic' 		=> $business_licence_pic,
			'bank_name'      	  	 	=> !empty($_POST['bank_name']) 				? $_POST['bank_name'] : '',
			'bank_branch_name'     		=> !empty($_POST['bank_branch_name']) 				? $_POST['bank_branch_name'] : '',
			'bank_account_number'  		=> !empty($_POST['bank_account_number'])		? $_POST['bank_account_number'] : '',
			'bank_address'         		=> !empty($_POST['bank_address']) 			? $_POST['bank_address'] : '',
		);
		RC_DB::table('store_franchisee')->where('store_id', $store_id)->update($data);

		$this->showmessage(RC_Lang::get('store::store.edit_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin/edit', array('store_id' => $store_id))));
	}

	/**
	 * 查看商家详细信息
	 */
	public function preview() {
		$this->admin_priv('store_affiliate_manage', ecjia::MSGTYPE_JSON);

		$this->assign('ur_here',RC_Lang::get('store::store.view'));
		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.view')));

		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$store['confirm_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['confirm_time']);

		$this->assign('store', $store);

		$this->display('store_preview.dwt');
	}

	/**
	 * 锁定商家
	 */
	public function status() {
		$this->admin_priv('store_affiliate_lock', ecjia::MSGTYPE_JSON);

		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));

		$store_id = $_GET['store_id'];
		$status   = $_GET['status'];
		if($status ==1){
			$this->assign('ur_here','锁定店铺');
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('锁定店铺'));
			$this->assign('status',$status);
		}else{
			$this->assign('ur_here','店铺解锁');
			ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('店铺解锁'));
		}

		$this->assign('form_action',RC_Uri::url('store/admin/status_update', array('store_id' => $store_id, 'status'=>$status)));

		$this->display('store_lock.dwt');
	}

	/**
	 * 锁定解锁商家操作
	 */
	public function status_update() {
		$this->admin_priv('store_affiliate_lock', ecjia::MSGTYPE_JSON);

		$store_id = $_GET['store_id'];
		$status   = $_GET['status'];

		if ($status == 1) {
			$status_new = 2;
		} elseif ($status == 2) {
			$status_new = 1;
		}

		RC_DB::table('store_franchisee')->where('store_id', $store_id)->update(array('status' => $status_new));

		$this->showmessage('操作成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin/init')));
	}

	/**
	 * 获取商家店铺经纬度
	 */
	public function get_longitude() {
		$detail_address = $_POST['detail_address'];
		$store_id = $_GET['store_id'];
		$store_point = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address='".$detail_address."'&output=json&ak=E70324b6f5f4222eb1798c8db58a017b");
		$store_point = (array)json_decode($store_point);
		$store_point['result'] = (array)$store_point['result'];
		$location = (array)$store_point['result']['location'];
		$longitude = $location['lng'];
		$latitude = $location['lat'];
		//获取geohash值
		$geohash = RC_Loader::load_app_class('geohash', 'store');
		$geohash_code = $geohash->encode($location['lat'] , $location['lng']);
		$geohash_code = substr($geohash_code, 0, 10);
		RC_DB::table('store_franchisee')->where('store_id', $store_id)->update(array('longitude' => $longitude, 'latitude' => $latitude, 'geohash' => $geohash_code));
		$data = array('longitude' => $longitude, 'latitude' => $latitude, 'geohash' => $geohash_code);
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $data));
	}


	//获取入驻商列表信息
	private function store_list() {
		$db_store_franchisee = RC_DB::table('store_franchisee as sf');

		$filter['keywords'] = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		$filter['type'] = empty($_GET['type']) ? '' : trim($_GET['type']);

		if ($filter['keywords']) {
		    $db_store_franchisee->where('merchants_name', 'like', '%'.mysql_like_quote($filter['keywords']).'%');
		}

		$filter_type = $db_store_franchisee
		->select(RC_DB::raw('count(*) as count_goods_num'),
		    RC_DB::raw('SUM(status = 1) as count_Unlock'),
		    RC_DB::raw('SUM(status = 2) as count_locking'))
		    ->first();

		$filter['count_goods_num'] = $filter_type['count_goods_num'];
		$filter['count_Unlock'] = $filter_type['count_Unlock'];
		$filter['count_locking'] = $filter_type['count_locking'];
		if (!empty($filter['type'])) {
		    $db_store_franchisee->where('status', $filter['type']);
		}

		$count = $db_store_franchisee->count();
		$page = new ecjia_page($count, 10, 5);

		$data = $db_store_franchisee
		->leftJoin('store_category as sc', RC_DB::raw('sf.cat_id'), '=', RC_DB::raw('sc.cat_id'))
		->selectRaw('sf.store_id,sf.merchants_name,sf.contact_mobile,sf.responsible_person,sf.confirm_time,sf.company_name,sf.sort_order,sc.cat_name,sf.status')
		->orderby('store_id', 'asc')
		->take(10)
		->skip($page->start_id-1)
		->get();

		$res = array();
		if (!empty($data)) {
			foreach ($data as $row) {
				$row['confirm_time'] = RC_Time::local_date(ecjia::config('time_format'), $row['confirm_time']);
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
