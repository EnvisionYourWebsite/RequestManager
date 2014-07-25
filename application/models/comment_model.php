<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Comment_model extends MY_Model {

		protected $table = 'comments';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			$this->set_primary_key('ID');
		}

		public function insert_comment($input) {
			return parent::insert($input);
		}

		public function delete_comment($id) {
			return parent::delete($id);
		}

		public function get_comment_content($id) {
			return parent::get_where(array('ID' => $id));
		}

		public function get_comment_id($where) {
			$query = parent::get_where($where);
			if ($query->num_rows() > 0) {
				return $query->row()->ID;
			}
			return FALSE;
		}


		public function set_parent($id,$parentId) {
			return parent::update($id,array('ParentID' => $parentId));
		}

		public function remove_user_comments($userId) {
			return parent::delete_where(array('UserID' => $userId));
		}

		public function hasParent($id) {
			$query = parent::get_where(array('ID' => $id));
			if ($query->num_rows > 0) {
				if ($query->row()->ParentID == NULL) 
					return FALSE;
				return TRUE;
			}
			return FALSE;
		}
 

	}







?>