<?php defined('BASEPATH') OR exit('No direct script access allowed');
		
		class Profile_user extends MY_Model {

			protected $table = 'profiles_users';

			public function __construct() {
				parent::__construct();
				$this->set_table_name($this->table);
				$this->set_primary_key('ID');
			}

			public function insert_profile($input) {
				$input['last_login'] = date('Y-m-d H:i:s');
				$input['ip_address'] = $this->input->ip_address();
				return parent::insert($input);
			}

			public function delete_user($id) {
				return parent::delete($id);
			}

			public function get_user_name($user_id) {
				$query = parent::get_where(array('user_id' => $user_id));
				if ($query->num_rows() > 0) {
					return $query->row()->name;
				}
				return FALSE;
			}

			public function get_associated_id($id,$label) {
				$query = parent::get_where(array($label => $id)); 
				if ($query->num_rows() > 0) {
					return $query->row()->user_id;
				}
				return FALSE; 
			}

			public function remove_user_profile_id($user_id) {
				return parent::delete_where(array('user_id' => $user_id));
			}

			public function get_user_id_profile($id) {
				$query = parent::get_where(array('user_id' => $id));
				if ($query->num_rows() > 0) {
					return $query->row();
				}
				return FALSE;
			}


		}
?>