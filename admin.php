<?php
/**
 * 入驻商家管理
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin extends ecjia_admin {
	private $db_region;
	public function __construct() {
		parent::__construct();

		$this->db_region = RC_Model::model('store/region_model');
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
		RC_Script::enqueue_script('store_log', RC_App::apps_url('statics/js/store_log.js', __FILE__));
		RC_Script::enqueue_script('commission_info',RC_App::apps_url('statics/js/commission.js' , __FILE__));
		RC_Script::enqueue_script('region',RC_Uri::admin_url('statics/lib/ecjia-js/ecjia.region.js'));

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
	    $cat_list = $this->get_cat_select_list();

	    $this->assign('cat_list', $cat_list);
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

		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('编辑入驻商'));

		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$store['confirm_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['confirm_time']);
		$cat_list = $this->get_cat_select_list();
		$certificates_list = array(
				'1' => RC_Lang::get('store::store.people_id'),
				'2' => RC_Lang::get('store::store.passport'),
				'3' => RC_Lang::get('store::store.hong_kong_and_macao_pass')
		);

		$province   = $this->db_region->get_regions(1, 1);
		$city       = $this->db_region->get_regions(2, $store['province']);
		$district   = $this->db_region->get_regions(3, $store['city']);
		$this->assign('province', $province);
		$this->assign('city', $city);
		$this->assign('district', $district);

		$this->assign('cat_list', $cat_list);
		$this->assign('certificates_list', $certificates_list);
		$this->assign('store', $store);
		$this->assign('form_action', RC_Uri::url('store/admin/update'));
		$this->assign('longitudeForm_action', RC_Uri::url('store/admin/get_longitude'));
		$this->assign('step', $_GET['step']);
		$this->assign('ur_here', $store['merchants_name']. ' - ' .RC_Lang::get('store::store.store_update'));

		$this->display('store_edit.dwt');
	}

	/**
	 * 编辑入驻商数据更新
	 */
	public function update() {
		$this->admin_priv('store_affiliate_update', ecjia::MSGTYPE_JSON);

		$store_id = intval($_POST['store_id']);
		$step = trim($_POST['step']);
		if (! in_array($step, array('base', 'identity', 'bank', 'pic'))) {
		    $this->showmessage('操作异常，请检查', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$store_info = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();

		if (!$store_info) {
		    $this->showmessage('店铺信息不存在', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		if ($step == 'base') {
		    $data = array(
		        'cat_id'   	   				=> !empty($_POST['store_cat']) 		? $_POST['store_cat'] : '',
		        'merchants_name'   			=> !empty($_POST['merchants_name']) ? $_POST['merchants_name'] : '',
		        'shop_keyword'      		=> !empty($_POST['shop_keyword']) 	? $_POST['shop_keyword'] : '',
		        'email'      				=> !empty($_POST['email']) 				? $_POST['email'] : '',
		        'contact_mobile'    		=> !empty($_POST['contact_mobile']) 	? $_POST['contact_mobile'] : '',
		        'address'      				=> !empty($_POST['address']) 			? $_POST['address'] : '',
		        'province'					=> !empty($_POST['province'])				? $_POST['province'] : '',
		        'city'						=> !empty($_POST['city'])					? $_POST['city'] : '',
		        'district'					=> !empty($_POST['district'])				? $_POST['district'] : '',
		    );
		} else if ($step == 'identity') {
		    $data = array(
		        'responsible_person'		=> !empty($_POST['responsible_person']) ? $_POST['responsible_person'] : '',
		        'company_name'      		=> !empty($_POST['company_name']) 		? $_POST['company_name'] : '',
		        'identity_type'     		=> !empty($_POST['identity_type']) 		? $_POST['identity_type'] : '',
		        'identity_number'   		=> !empty($_POST['identity_number']) 	? $_POST['identity_number'] : '',
		        'identity_pic_front'		=> $identity_pic_front,
		        'identity_pic_back' 		=> $identity_pic_back,
		        'personhand_identity_pic' 	=> $personhand_identity_pic,
		        'business_licence_pic' 		=> $store_info['validate_type']  == 2 ? $business_licence_pic : null,
		        'business_licence'      	=> !empty($_POST['business_licence']) 		? $_POST['business_licence'] : '',
		    );
		} else if ($step == 'bank') {
		    $data = array(
		        'bank_account_name'  		=> !empty($_POST['bank_account_name']) 	? $_POST['bank_account_name'] : '',
		        'bank_name'      	  	 	=> !empty($_POST['bank_name']) 				? $_POST['bank_name'] : '',
		        'bank_branch_name'     		=> !empty($_POST['bank_branch_name']) 		? $_POST['bank_branch_name'] : '',
		        'bank_account_number'  		=> !empty($_POST['bank_account_number'])	? $_POST['bank_account_number'] : '',
		        'bank_address'         		=> !empty($_POST['bank_address']) 			? $_POST['bank_address'] : '',
		    );
		} else if ($step == 'pic') {
		    if (!empty($_FILES['one']['name'])) {
		        $upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
		        $info = $upload->upload($_FILES['one']);
		        if (!empty($info)) {
		            $business_licence_pic = $upload->get_position($info);
		            $upload->remove($store_info['business_licence_pic']);
		        } else {
		            $this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		        }
		    } else {
		        $business_licence_pic = $store_info['business_licence_pic'];
		    }

		    if (!empty($_FILES['two']['name'])) {
		        $upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
		        $info = $upload->upload($_FILES['two']);
		        if (!empty($info)) {
		            $identity_pic_front = $upload->get_position($info);
		            $upload->remove($store_info['identity_pic_front']);
		        } else {
		            $this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		        }
		    } else {
		        $identity_pic_front = $store_info['identity_pic_front'];
		    }

		    if (!empty($_FILES['three']['name'])) {
		        $upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
		        $info = $upload->upload($_FILES['three']);
		        if (!empty($info)) {
		            $identity_pic_back = $upload->get_position($info);
		            $upload->remove($store_info['identity_pic_back']);
		        } else {
		            $this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		        }
		    } else {
		        $identity_pic_back = $store_info['identity_pic_back'];
		    }

		    if (!empty($_FILES['four']['name'])) {
		        $upload = RC_Upload::uploader('image', array('save_path' => 'data/store', 'auto_sub_dirs' => false));
		        $info = $upload->upload($_FILES['four']);
		        if (!empty($info)) {
		            $personhand_identity_pic = $upload->get_position($info);
		            $upload->remove($store_info['personhand_identity_pic']);
		        } else {
		            $this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		        }
		    } else {
		        $personhand_identity_pic = $store_info['personhand_identity_pic'];
		    }

		    $data = array(
		        'identity_pic_front'		=> $identity_pic_front,
		        'identity_pic_back' 		=> $identity_pic_back,
		        'personhand_identity_pic' 	=> $personhand_identity_pic,
		        'business_licence_pic' 		=> $store_info['validate_type']  == 2 ? $business_licence_pic : null,
		    );
		}



		/* $data = array(
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
			'bank_account_name'  		=> !empty($_POST['bank_account_name']) 	? $_POST['bank_account_name'] : '',
			'business_licence_pic' 		=> $store_info['validate_type']  == 2 ? $business_licence_pic : null,
			'business_licence'      	=> !empty($_POST['business_licence']) 		? $_POST['business_licence'] : '',
			'bank_name'      	  	 	=> !empty($_POST['bank_name']) 				? $_POST['bank_name'] : '',
			'bank_branch_name'     		=> !empty($_POST['bank_branch_name']) 		? $_POST['bank_branch_name'] : '',
			'bank_account_number'  		=> !empty($_POST['bank_account_number'])	? $_POST['bank_account_number'] : '',
			'province'					=> !empty($_POST['province'])				? $_POST['province'] : '',
			'city'						=> !empty($_POST['city'])					? $_POST['city'] : '',
		    'district'					=> !empty($_POST['district'])				? $_POST['district'] : '',
			'bank_address'         		=> !empty($_POST['bank_address']) 			? $_POST['bank_address'] : '',
		); */

		$sn =  RC_DB::table('store_franchisee')->where('store_id', $store_id)->update($data);
		ecjia_admin::admin_log(RC_Lang::get('store::store.edit_store').' '.RC_Lang::get('store::store.store_title_lable').$store_info['merchants_name'], 'update', 'store');
		$this->showmessage(RC_Lang::get('store::store.edit_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS,
		    array('pjaxurl' => RC_Uri::url('store/admin/edit', array('store_id' => $store_id, 'step' => $step))));
	}

	/**
	 * 查看商家详细信息
	 */
	public function preview() {
		$this->admin_priv('store_affiliate_manage', ecjia::MSGTYPE_JSON);

		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.view')));

		$store_id = intval($_GET['store_id']);
		$store = RC_DB::table('store_franchisee')->where('store_id', $store_id)->first();
		$store['apply_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['apply_time']);
		$store['confirm_time']	= RC_Time::local_date(ecjia::config('time_format'), $store['confirm_time']);

		$store['province'] = RC_DB::table('region')->where('region_id', $store['province'])->pluck('region_name');
		$store['city'] = RC_DB::table('region')->where('region_id', $store['city'])->pluck('region_name');
		$store['district'] = RC_DB::table('region')->where('region_id', $store['district'])->pluck('region_name');

		$this->assign('ur_here', $store['merchants_name']/*  .'-'. RC_Lang::get('store::store.view') */);
		$store['cat_name'] = RC_DB::table('store_category')->where('cat_id', $store['cat_id'])->select('cat_name')->pluck();
		if ($store['percent_id']) {
		    $store['percent_value'] = RC_DB::table('store_percent')->where('percent_id', $store['percent_id'])->select('percent_value')->pluck();
		}

		$this->assign('store', $store);
		$this->display('store_preview.dwt');
	}

	//店铺设置
	public function store_set() {


	    $this->display('store_preview.dwt');
	}

	//店铺设置修改
	public function store_set_update() {


	}

	/**
	 * 查看员工
	 */
	public function view_staff() {
		$this->admin_priv('store_affiliate_manage', ecjia::MSGTYPE_JSON);
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.view_staff')));

		$store_id = intval($_GET['store_id']);
		$main_staff = RC_DB::table('staff_user')->where('store_id', $store_id)->where('parent_id', 0)->first();
		$parent_id = $main_staff['user_id'];

		$staff_list = RC_DB::table('staff_user')->where('parent_id', $parent_id)->get();

		$this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
		$this->assign('ur_here',RC_Lang::get('store::store.view_staff'));
		$this->assign('main_staff', $main_staff);
		$this->assign('staff_list', $staff_list);

		$this->assign('store', $store);
		$this->display('store_staff.dwt');
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

		$this->showmessage('操作成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin/preview', array('store_id' => $store_id))));
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
		$filter['cat'] = empty($_GET['cat']) ? null : trim($_GET['cat']);

		if ($filter['keywords']) {
		    $db_store_franchisee->where('merchants_name', 'like', '%'.mysql_like_quote($filter['keywords']).'%');
		}
		if ($filter['cat']) {
		    if ($filter['cat'] == -1) {
		        $db_store_franchisee->whereRaw('sf.cat_id=0');
		    } else {
		        $db_store_franchisee->whereRaw('sf.cat_id='.$filter['cat']);
		    }
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
		$page = new ecjia_page($count, 20, 5);

		$data = $db_store_franchisee
		->leftJoin('store_category as sc', RC_DB::raw('sf.cat_id'), '=', RC_DB::raw('sc.cat_id'))
		->selectRaw('sf.store_id,sf.merchants_name,sf.contact_mobile,sf.responsible_person,sf.confirm_time,sf.company_name,sf.sort_order,sc.cat_name,sf.status')
		->orderby('store_id', 'asc')
		->take($page->page_size)
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

	/**
	 * 获取指定地区的子级地区
	 */
	public function get_region(){
		$type      = !empty($_GET['type'])   ? intval($_GET['type'])   : 0;
		$parent        = !empty($_GET['parent']) ? intval($_GET['parent']) : 0;
		$arr['regions'] = $this->db_region->get_regions($type, $parent);
		$arr['type']    = $type;
		$arr['target']  = !empty($_GET['target']) ? stripslashes(trim($_GET['target'])) : '';
		$arr['target']  = htmlspecialchars($arr['target']);
		echo json_encode($arr);
	}

    /**
	 * 查看店铺日志
	 */
    public function view_log(){
        $this->admin_priv('store_affiliate_manage', ecjia::MSGTYPE_JSON);
        $this->assign('action_link',array('href' => RC_Uri::url('store/admin/init'),'text' => RC_Lang::get('store::store.store_list')));
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('store::store.view')));
        $store_id = intval($_GET['store_id']);
        if(empty($store_id)){
            $this->showmessage(__('请选择商家店铺'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }

        $store_jslang = array(
			'choose_delet_time'	=> __('请先选择删除日志的时间！'),
			'delet_ok_1'		=> __('确定删除'),
			'delet_ok_2'		=> __('的日志吗？'),
			'ok'				=> __('确定'),
			'cancel'			=> __('取消')
		);
		RC_Script::localize_script('store', 'store', $store_jslang );

        $merchants_name = RC_DB::table('store_franchisee')->where('store_id', $store_id)->pluck('merchants_name');

        $this->assign('ur_here', $merchants_name);

        $logs = $this->get_admin_logs($_REQUEST,$store_id);
        $user_id    = !empty($_REQUEST['userid']) ? intval($_REQUEST['userid']) : 0;
        $ip         = !empty($_REQUEST['ip']) ? $_REQUEST['ip'] : '';
        $keyword    = !empty($_REQUEST['keyword']) ? trim(htmlspecialchars($_REQUEST['keyword'])) : '';

        $this->assign('user_id',    $user_id);
        $this->assign('ip',         $ip);
        $this->assign('keyword',    $keyword);
		/* 查询IP地址列表 */
		$ip_list = array();
		$data = RC_DB::table('staff_log')->selectRaw('distinct ip_address')->get();
		if (!empty($data)) {
			foreach ($data as $row) {
				$ip_list[] = $row['ip_address'];
			}
		}

		/* 查询管理员列表 */
		$user_list = array();
		$userdata = RC_DB::table('staff_user')->where(RC_DB::raw('store_id'), $store_id)->get();
		if (!empty($userdata)) {
			foreach ($userdata as $row) {
				if (!empty($row['user_id']) && !empty($row['name'])) {
					$user_list[$row['user_id']] = $row['name'];
				}
			}
		}

		$this->assign('form_search_action', RC_Uri::url('store/admin/view_log', array('store_id' => $store_id)));

		$this->assign('logs', $logs);
		$this->assign('ip_list',   $ip_list);
		$this->assign('user_list',   $user_list);
        $this->display('staff_log.dwt');
    }

    /**
	 *  获取管理员操作记录
	 *  @param array $_GET , $_POST, $_REQUEST 参数
	 * @return array 'list', 'page', 'desc'
	 */
	private function get_admin_logs($args = array(),$store_id) {
		$db_staff_log = RC_DB::table('staff_log as sl')
						->leftJoin('staff_user as su', RC_DB::raw('sl.user_id'), '=', RC_DB::raw('su.user_id'));

		$user_id  = !empty($args['userid']) ? intval($args['userid']) : 0;
		$ip = !empty($args['ip']) ? $args['ip'] : '';


		$filter = array();
		$filter['sort_by']      = !empty($args['sort_by']) ? safe_replace($args['sort_by']) :  RC_DB::raw('sl.log_id');
		$filter['sort_order']   = !empty($args['sort_order']) ? safe_replace($args['sort_order']) : 'DESC';

		$keyword = !empty($args['keyword']) ? trim(htmlspecialchars($args['keyword'])) : '';

		//查询条件
		$where = array();
		if (!empty($ip)) {
			$db_staff_log->where(RC_DB::raw('sl.ip_address'), $ip);
		}

		if(!empty($keyword)) {
			$db_staff_log->where(RC_DB::raw('sl.log_info'), 'like', '%'.$keyword.'%');
		}

		if (!empty($user_id)) {
			$db_staff_log->where(RC_DB::raw('su.user_id'), $user_id);
		}
        $db_staff_log->where(RC_DB::raw('su.store_id'), $store_id);

		$count = $db_staff_log->count();
		$page = new ecjia_page($count, 15, 5);
		$data = $db_staff_log
		->selectRaw('sl.log_id,sl.log_time,sl.log_info,sl.ip_address,sl.ip_location,su.name')
		->orderby($filter['sort_by'], $filter['sort_order'])
		->take(10)
		->skip($page->start_id-1)
		->get();
		/* 获取管理员日志记录 */
		$list = array();
		if (!empty($data)) {
			foreach ($data as $rows) {
				$rows['log_time'] = RC_Time::local_date(ecjia::config('time_format'), $rows['log_time']);
				$list[] = $rows;
			}
		}
		return array('list' => $list, 'page' => $page->show(5), 'desc' => $page->page_desc());
	}

    /**
     * 批量删除管理员操作日志
     */
    public function batch_drop(){
        $this->admin_priv('store_affiliate_manage', ecjia::MSGTYPE_JSON);

        $drop_type_date = isset($_POST['drop_type_date']) ? $_POST['drop_type_date'] : '';
        $staff_log = RC_DB::table('staff_log');
        $store_id = $_GET['store_id'];
		/* 按日期删除日志 */
		if ($drop_type_date) {
			if ($_POST['log_date'] > 0) {
				$where = array();
				switch ($_POST['log_date']) {
					case 1:
						$a_week = RC_Time::gmtime() - (3600 * 24 * 7);
						$staff_log->where('log_time', '<=',$a_week);
						$deltime = __('一周之前');
					break;
					case 2:
						$a_month = RC_Time::gmtime() - (3600 * 24 * 30);
                        $staff_log->where('log_time', '<=',$a_month);
						$deltime = __('一个月前');
					break;
					case 3:
						$three_month = RC_Time::gmtime() - (3600 * 24 * 90);
                        $staff_log->where('log_time', '<=',$three_month);
						$deltime = __('三个月前');
					break;
					case 4:
						$half_year = RC_Time::gmtime() - (3600 * 24 * 180);
                        $staff_log->where('log_time', '<=',$half_year);
						$deltime = __('半年之前');
					break;
					case 5:
					default:
						$a_year = RC_Time::gmtime() - (3600 * 24 * 365);
						$where['log_time'] = array('elt' => $a_year);
                        $staff_log->where('log_time', '<=',$a_year);
						$deltime = __('一年之前');
					break;
				}

				RC_DB::table('staff_log')->where('store_id', $store_id)->delete();
                $this->showmessage(sprintf(__('%s 的日志成功删除。'), $deltime), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('store/admin/view_log', array('store_id' => $store_id))));
            }
        }else{
            $this->showmessage(__('请选择日期'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
    }

}

//end
