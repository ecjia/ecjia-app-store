<?php
defined('IN_ECJIA') or exit('No permission resources.');

class region_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->table_name 	= 'region';
		parent::__construct();
	}
}

// end