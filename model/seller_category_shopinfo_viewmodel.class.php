<?php
defined('IN_ECJIA') or exit('No permission resources.');

class seller_category_shopinfo_viewmodel extends Component_Model_View {
	public $table_name = '';
	public  $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'seller_category';
		$this->table_alias_name = 'c';
		
		$this->view = array(
				'seller_shopinfo' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' =>	's',
						'field' =>	'',
						'on'   	=>	's.cat_id = c.cat_id'
				)				
		);
		parent::__construct();
	}
}

// end