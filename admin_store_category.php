<?php
/**
 * 店铺分类管理
 */
defined('IN_ECJIA') or exit('No permission resources.');
RC_Loader::load_sys_class('ecjia_admin', false);

class admin_store_category extends ecjia_admin {
	private $seller_category_db;
	private $seller_shopinfo_db;
	
	public function __construct() {
		
		parent::__construct();
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
		
		RC_Loader::load_app_func('store_category','store');
		
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
		
		RC_Script::enqueue_script('jquery.toggle.buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/jquery.toggle.buttons.js'));
		RC_Style::enqueue_style('bootstrap-toggle-buttons', RC_Uri::admin_url('statics/lib/toggle_buttons/bootstrap-toggle-buttons.css'));
		
		RC_Script::enqueue_script('store_category', RC_App::apps_url('statics/js/store_category.js', __FILE__), array(), false, true);
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('店铺分类'), RC_Uri::url('seller/admin_store_category/init')));
	}
	
	/**
	 * 店铺分类列表
	 */
	public function init() {
	    $this->admin_priv('store_category_manage',ecjia::MSGTYPE_JSON);
		
	    ecjia_screen::get_current_screen()->remove_last_nav_here();
	    ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('店铺分类')));
// 	    ecjia_screen::get_current_screen()->add_help_tab( array(
// 	    'id'		=> 'overview',
// 	    'title'		=> __('概述'),
// 	    'content'	=>
// 	    '<p>' . __('欢迎访问ECJia智能后台入驻商列表页面，系统中所有的入驻商家都会显示在此列表中。') . '</p>'
// 	    ) );
	    
// 	    ecjia_screen::get_current_screen()->set_help_sidebar(
// 	    '<p><strong>' . __('更多信息:') . '</strong></p>' .
// 	    '<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:入驻商家" target="_blank">关于入驻商列表帮助文档</a>') . '</p>'
// 	    );
	   
	    $cat_list = cat_list(0, 0, false);
	    $this->assign('cat_info', $cat_list);
	    $this->assign('ur_here',__('店铺分类'));
	    $this->assign('action_link', array('text' => __('添加分类'),'href'=>RC_Uri::url('store/admin_store_category/add')));
	    $this->display('store_category_list.dwt');
	}
	
	/**
	 * 添加店铺分类
	 */
	public function add() {
	    $this->admin_priv('store_category_manage');
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(_('添加店铺分类')));
		$this->assign('ur_here', _('添加分类'));
		$this->assign('action_link',  array('href' => RC_Uri::url('store/admin_store_category/init'), 'text' => _('店铺分类')));
		
		$this->assign('cat_select', cat_list(0, 0, true));
		$this->assign('form_action', RC_Uri::url('store/admin_store_category/insert'));

// 		ecjia_screen::get_current_screen()->add_help_tab(array(
// 			'id'		=> 'overview',
// 			'title'		=> __('概述'),
// 			'content'	=>
// 			'<p>' . __('欢迎访问ECJia智能后台添加商品分类页面，可以在此页面添加分类信息。') . '</p>'
// 		));

// 		ecjia_screen::get_current_screen()->set_help_sidebar(
// 			'<p><strong>' . __('更多信息:') . '</strong></p>' .
// 			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品分类#.E6.B7.BB.E5.8A.A0.E5.95.86.E5.93.81.E5.88.86.E7.B1.BB" target="_blank">关于添加商品分类文档</a>') . '</p>'
// 		);

		$this->display('store_category_info.dwt');
	}

	/**
	 * 店铺分类添加时的处理
	 */
	public function insert() {
		$this->admin_priv('store_category_manage', ecjia::MSGTYPE_JSON);
		
		$cat['cat_name']     = !empty($_POST['cat_name'])     ? trim($_POST['cat_name'])     : '';
		$cat['parent_id'] 	 = !empty($_POST['store_cat_id']) ? intval($_POST['store_cat_id'])  : 0;
		$cat['sort_order']   = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
		$cat['is_show'] 	 = isset($_POST['is_show']) ? 1 : 0;
		$cat['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
		$cat['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
	
		if (cat_exists($cat['cat_name'], $cat['parent_id'])) {
			$this->showmessage('已存在相同的分类名称!', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		/*分类图片上传*/
		$upload = RC_Upload::uploader('image', array('save_path' => 'data/store_category', 'auto_sub_dirs' => true));
		if (isset($_FILES['cat_image']) && $upload->check_upload_file($_FILES['cat_image'])) {
			$image_info = $upload->upload($_FILES['cat_image']);
			if (empty($image_info)) {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
			$cat['cat_image'] = $upload->get_position($image_info);
		}
		
		/* 入库的操作 */
		$insert_id = RC_DB::table('store_category')->insertGetId($cat);
		if ($insert_id) {
			ecjia_admin::admin_log($_POST['cat_name'], 'add', 'store_category');   // 记录管理员操作
			/*添加链接*/
			$links[] = array('text' => '店铺分类列表', 'href'=> RC_Uri::url('store/admin_store_category/init'));
			$links[] = array('text' => '继续添加分类', 'href'=> RC_Uri::url('store/admin_store_category/add'));
			$this->showmessage('添加店铺分类成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links ,'pjaxurl' => RC_Uri::url('store/admin_store_category/edit', array('cat_id' => $insert_id))));
		} else {
			$this->showmessage('添加店铺分类失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	
	/**
	 * 编辑店铺分类信息
	 */
	public function edit() {
		$this->admin_priv('store_category_manage');
		$cat_id = intval($_GET['cat_id']);
		$cat_info = get_cat_info($cat_id);  // 查询分类信息数据
		
		if(!empty($cat_info['cat_image'])) {
			$cat_info['cat_image'] =  RC_Upload::upload_url($cat_info['cat_image']);
		} else {
			$cat_info['cat_image'] = '';
		}
	
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(_('编辑店铺分类')));
		$this->assign('ur_here', _('编辑店铺分类'));
		$this->assign('action_link', array('text' => _('编辑店铺分类'), 'href' => RC_Uri::url('store/admin_store_category/init')));

		$this->assign('cat_info', $cat_info);
		$this->assign('cat_select', cat_list(0, $cat_info['parent_id'], true));
		
		$this->assign('form_action', RC_Uri::url('store/admin_store_category/update'));
	
// 		ecjia_screen::get_current_screen()->add_help_tab(array(
// 		'id'		=> 'overview',
// 		'title'		=> __('概述'),
// 		'content'	=>
// 		'<p>' . __('欢迎访问ECJia智能后台编辑商品分类页面，可以在此对相应的商品分类进行编辑。') . '</p>'
// 				));
	
// 		ecjia_screen::get_current_screen()->set_help_sidebar(
// 		'<p><strong>' . __('更多信息:') . '</strong></p>' .
// 		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:商品分类#.E7.BC.96.E8.BE.91.E5.95.86.E5.93.81.E5.88.86.E7.B1.BB" target="_blank">关于编辑商品分类文档</a>') . '</p>'
// 				);

		$this->display('store_category_info.dwt');
	}
	
	/**
	 * 编辑商品分类信息
	 */
	public function update() {
		$this->admin_priv('store_category_manage', ecjia::MSGTYPE_JSON);
		
		$cat_id              = !empty($_POST['cat_id'])       ? intval($_POST['cat_id'])     : 0;
		$cat['cat_name']     = !empty($_POST['cat_name'])     ? trim($_POST['cat_name'])     : '';
		$cat['parent_id']	 = !empty($_POST['store_cat_id'])    ? intval($_POST['store_cat_id'])  : 0;
		$cat['sort_order']   = !empty($_POST['sort_order'])   ? intval($_POST['sort_order']) : 0;
		$cat['is_show'] 	 = isset($_POST['is_show']) ? 1 : 0;
		$cat['keywords']     = !empty($_POST['keywords'])     ? trim($_POST['keywords'])     : '';
		$cat['cat_desc']     = !empty($_POST['cat_desc'])     ? $_POST['cat_desc']           : '';
		
		$old_cat_name     	= !empty($_POST['old_cat_name '])     ? trim($_POST['old_cat_name '])     : '';
		
		/* 判断分类名是否重复 */
		if ($cat['cat_name'] != $old_cat_name) {
			if (cat_exists($cat['cat_name'], $cat['parent_id'], $cat_id)) {
				$this->showmessage('已存在相同的分类名称!', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}	
		/* 判断上级目录是否合法 */
		$children = array_keys(cat_list($cat_id, 0, false));     // 获得当前分类的所有下级分类
		if (in_array($cat['parent_id'], $children)) {
			/* 选定的父类是当前分类或当前分类的下级分类 */
			$this->showmessage(_('所选择的上级分类不能是当前分类或者当前分类的下级分类!'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		/* 更新分类图片 */
		$upload = RC_Upload::uploader('image', array('save_path' => 'data/category', 'auto_sub_dirs' => true));
		
		if (isset($_FILES['cat_image']) && $upload->check_upload_file($_FILES['cat_image'])) {
			$image_info = $upload->upload($_FILES['cat_image']);
			if (empty($image_info)) {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
			$cat['cat_image'] = $upload->get_position($image_info);
		}
		//$update_id = $this->seller_category_db->where(array('cat_id' => $cat_id))->update($cat);
		RC_DB::table('store_category')
						->where('cat_id', $cat_id)
						->update($cat);
		/*记录log */
		ecjia_admin::admin_log($_POST['cat_name'], 'edit', 'store_category');
		$link[] = array('text' => _('返回店铺分类列表'), 'href' => RC_Uri::url('store/admin_store_category/init'));
		$this->showmessage('编辑店铺分类成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $link, 'id' => $cat_id));
	}
	
	/**
	 * 删除店铺分类
	 */
	public function remove() {
		$this->admin_priv('store_category_drop', ecjia::MSGTYPE_JSON);
		/* 初始化分类ID并取得分类名称 */
		$cat_id   = intval($_GET['id']);
		$cat_name = $this->seller_category_db->where(array('cat_id' => $cat_id))->get_field('cat_name');
		/* 当前分类下是否有子分类 */
		$cat_count = $this->seller_category_db->where(array('parent_id' => $cat_id))->count();
		/* 当前分类下是否存在店铺 */
		$shop_count = $this->seller_shopinfo_db->where(array('cat_id' => $cat_id))->count();
		/* 如果不存在下级子分类和商品，则删除之 */
	
		if ($cat_count == 0 && $shop_count == 0) {
			/* 删除分类 */
			$query = $this->seller_category_db->where(array('cat_id' => $cat_id))->delete();
			if ($query) {
				//记录log
				ecjia_admin::admin_log($cat_name, 'remove', 'store_category');
				$this->showmessage(_('删除店铺分类成功！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
			}
		} else {
			$this->showmessage($cat_name .' '. '不是末级分类或者此分类下还存在有店铺，您不能删除!', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 切换是否显示
	 */
	public function toggle_is_show() {
		$this->admin_priv('store_category_manage', ecjia::MSGTYPE_JSON);
	
		$id = intval($_POST['id']);
		$val = intval($_POST['val']);
		$cat_name = $this->seller_category_db->where(array('cat_id' => $id))->get_field('cat_name');
		if (cat_update($id, array('is_show' => $val))) {
			//记录log
			ecjia_admin::admin_log($cat_name, 'edit', 'store_category');
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $val));
		} else {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 编辑排序序号
	 */
	public function edit_sort_order() {
		$this->admin_priv('store_category_manage', ecjia::MSGTYPE_JSON);
	
		$id = intval($_POST['pk']);
		$val = intval($_POST['value']);
		$cat_name = $this->seller_category_db->where(array('cat_id' => $id))->get_field('cat_name');
		if (cat_update($id, array('sort_order' => $val))) {
			//记录log
			ecjia_admin::admin_log($cat_name, 'edit', 'store_category');
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('seller/admin_store_category/init')));
		} else {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
}

//end