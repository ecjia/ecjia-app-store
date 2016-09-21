<?php
defined('IN_ECJIA') or exit('No permission resources.');

class cart_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'cart';
		$this->table_alias_name = 'c';
		
		$this->view =array(
				'goods' => array(
						'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'g',
						'on' 	=> 'c.goods_id = g.goods_id'
				),
				'merchants_shop_information' => array(
						'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'msi',
						'on' 	=> 'msi.user_id = c.ru_id'
				),
		);
		
		parent::__construct();
	}
}

// end