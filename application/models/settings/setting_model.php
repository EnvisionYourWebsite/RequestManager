<?php defined('BASEPATH') OR exit('No direct script access allowed');

	
	class Setting_model extends MY_Model {

		protected $table = 'settings';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			parent::set_primary_key('OptionName');

		}

		public function get($where)
		{
			if(!is_array($where))
			{
				$where = array($this->key => $where);
			}

			return parent::get_where($where); 
		}

		public function find_all_by($field=NULL,$value=NULL)
		{
			if(empty($field)) return FALSE;

			$this->db->where($field,$value); 

			$results = $this->find_all();

			$return = array(); 

			if(is_array($results) && count($results))
			{
				foreach($results as $result)
				{
					$return[$record->Option_name] = $record->value;
				}
			}

			return $return;
		}

		public function insert($option_name, $option_value) {
			return parent::insert(array('OptionName' => $option_name,'OptionValue' => $option_value, 'Default' => ''));
		}


		public function update($option_name, $value) {
			return parent::update($option_name, $value);
		}

	
	}
?>