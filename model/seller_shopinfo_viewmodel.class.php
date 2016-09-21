<?php
defined('IN_ECJIA') or exit('No permission resources.');

class seller_shopinfo_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'seller_shopinfo';
		$this->table_alias_name = 'ssi';
		
		$this->view =array(
				'seller_category' => array(
						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'sc',
						'on'    => 'ssi.cat_id = sc.cat_id',
				),
				'collect_store' => array(
						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'cs',
						'on'    => 'ssi.id = cs.seller_id',
				),
				'term_relationship' => array(
						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'tr',
						'on'    => 'tr.object_id = ssi.id and object_type="ecjia.merchant" and item_key1="merchant_adsense"',
				),
		);
		
		
		parent::__construct();
	}
}

// end