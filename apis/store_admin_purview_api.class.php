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
        	array('action_name' => RC_Lang::get('store::store.store_advance'), 'action_code' => 'store_advance_manage', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_update'), 'action_code' => 'store_advance_update', 	'relevance' => ''),
        	array('action_name' => RC_Lang::get('store::store.store_check'), 'action_code' => 'store_advance_check', 	'relevance' => ''),
        	
        );
        
        return $purviews;
    }
}

// end