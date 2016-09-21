<?php
defined('IN_ECJIA') or exit('No permission resources.');

class admin_config extends ecjia_admin {

	public function __construct() {
		parent::__construct();
		
		RC_Loader::load_app_func('global');
		assign_adminlog_content();
	
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('bootstrap-placeholder');
		
		RC_Script::enqueue_script('admin_config', RC_App::apps_url('statics/js/admin_config.js', __FILE__), array(), false, true);
	}
					
	/**
	 * 后台设置
	 */
	public function init() {
	    $this->admin_priv('merchants_config_manage');
	   
		$this->assign('ur_here', '后台配置');
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('后台配置')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台设置页面，可以在此页面对后台进行设置。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:后台设置" target="_blank">关于后台设置帮助文档</a>') . '</p>'
		);
		
    	$this->assign('config_cpname', ecjia::config('merchante_admin_cpname'));
    	$this->assign('config_logoimg', RC_Upload::upload_url(ecjia::config('merchante_admin_login_logo')));
    	$this->assign('config_logo', ecjia::config('merchante_admin_login_logo'));

		$this->assign('form_action', RC_Uri::url('seller/admin_config/update'));
		
		$this->assign_lang();
		$this->display('merchant_config_info.dwt');
	}
		
	/**
	 * 处理后台设置
	 */
	public function update() {
		$this->admin_priv('merchants_config_manage');

		ecjia_config::instance()->write_config('merchante_admin_cpname',  $_POST['merchante_admin_cpname']);//名称
		
		$upload = RC_Upload::uploader('image', array('save_path' => 'data/assets', 'save_name' => 'seller_admin_logo', 'replace' => true,'auto_sub_dirs' => false));
		$image_info = $upload->upload($_FILES['merchante_admin_login_logo']);		
		/* 判断是否上传成功 */
		if (!empty($image_info)) {
			$old_logo = ecjia::config('merchante_admin_login_logo');
			if (!empty($old_logo)) {
				$upload->remove($old_logo);
			}
// 			$logo = $image_info['savepath'].'/'.$image_info['savename'];
			$logo = $upload->get_position($image_info);	
			ecjia_config::instance()->write_config('merchante_admin_login_logo', $logo);
		}
		ecjia_admin::admin_log('商家入驻>后台设置', 'setup', 'config');
		$this->showmessage(__('更新后台设置成功！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('seller/admin_config/init')));
	}
	
	/**
	 * 删除上传文件
	 */
	public function del() {
		$this->admin_priv('merchants_config_manage');

// 		@unlink(RC_Upload::upload_path() . ecjia::config('merchante_admin_login_logo'));
		$disk = RC_Filesystem::disk();
		$disk->delete(RC_Upload::upload_path() . ecjia::config('merchante_admin_login_logo'));
		
		ecjia_config::instance()->write_config('merchante_admin_login_logo','');
		$this->showmessage(__('删除登陆后台logo成功！'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
}

//end