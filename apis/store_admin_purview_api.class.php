<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author songqian
 *
 */
class adsense_admin_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
        	//入驻商权限	
            array('action_name' => RC_Lang::get('store::store.store_affiliate'), 'action_code' => 'store_affiliate_manage', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_update'), 'action_code' => 'store_affiliate_update', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_lock'), 'action_code' => 'store_affiliate_lock', 	'relevance' => ''),
        		
        	//待审核入驻商权限
        	array('action_name' => RC_Lang::get('store::store.store_preaudit'), 'action_code' => 'store_preaudit_manage', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_update'), 'action_code' => 'store_preaudit_update', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_check'), 'action_code' => 'store_preaudit_check', 	'relevance' => ''),
        	
        );
        
        return $purviews;
    }
}

// end