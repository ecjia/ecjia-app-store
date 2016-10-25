<?php
defined('IN_ECJIA') or exit('No permission resources.');

class store_admin_plugin {
	
	static public function store_admin_menu_api($menus) {
		$menu = ecjia_admin::make_admin_menu('06_notice_list', '商家公告', RC_Uri::url('store/admin_notice/init'), 7)->add_purview('notice_manage');
	    $menus->add_submenu($menu);
	    return $menus;
	}
}

RC_Hook::add_filter( 'article_admin_menu_api', array('store_admin_plugin', 'store_admin_menu_api') );

// end