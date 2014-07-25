<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Usersettings_model extends MY_Model {

		protected $table = 'user_settings';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			$this->set_primary_key('UserID');
		}

		public function insert($input) {
			return parent::insert($input); 
		}

		public function update_setting($id,$input) {
			return parent::update($id,$input); 
		}

		public function get_setting($id) {
			$query = parent::get_where(array('UserID' => $id)); 
			if ($query->num_rows() > 0) 
				return $query->row(); 
			return FALSE; 
		}

		public function insert_or_update($data,$id) {
			if ($this->num_rows_where(array('UserID' => $id)) > 0) {
				return parent::update($id,$data);
			}
			else
			{
				$data['UserID'] = $id;
				return parent::insert($data);
			}
		}	
 

	}







?>