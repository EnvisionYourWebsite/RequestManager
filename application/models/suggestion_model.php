<?php defined('BASEPATH') OR exit('No direct script access allowed');

	
	class suggestion_model extends MY_Model {

		protected $table = 'suggestions';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			$this->set_primary_key('ID');
		}


		public function insert_suggestion($input) {
			$input['CreatedOn'] = date('Y-m-d H:i:s');
			return parent::insert($input);
		}

		public function update_suggestion($id,$input) {
			$input['UpdatedOn'] = date('Y-m-d H:i:s');
			return parent::update($id,$input);
		}

		public function remove_suggestion($id) {
			return parent::delete($id);
		}

		public function remove_user_suggestions($userId) {
			return parent::delete_where(array('UserID' => $userId));
		}

	// 0 => New
 	// 1 => Pending
 	// 2 => Planned
 	// 3 => Started 
 	// 4 => Completed
		public function set_suggestion_status($id,$status) {
			return parent::update($id,array('Status' => $status,'status_updated_on' => date('Y-m-d H:i:s')));
		}

		public function update_suggestion_last_status($id,$status) {
			return parent::update($id,array('Last_status' => $status));
		}

		public function get_suggestion_status($id) {
			$query = parent::get_where(array('ID' => $id)); 
			if ($query->num_rows()> 0) {
				return $query->row()->Status; 
			}
			return FALSE; 
		}

		// 
		public function get_suggestion_content($id) {
			$query =  parent::get_where(array('ID' => $id));
			if ($query->num_rows > 0) {
				return $query->result();
			}
			return FALSE;
		}

		public function set_slug($title) {
			
		}

		public function slug_exists($slug) {
			return parent::record_exists(array('Slug' => $slug)); 
		}

		public function get_suggestion_slug($id) {
			$query = parent::get_where(array('ID' => $id)); 
			if ($query->num_rows() > 0) {
				return $query->row()->Slug;
			}
			return FALSE;
		}

		public function get_suggestion_id($slug) {
			$query = parent::get_where(array('Slug' => $slug)); 
			if ($query->num_rows() > 0) {
				return $query->row()->ID;
			}
			return FALSE;
		}

		public function count_suggestions_by_status($status) {
			$query = parent::get_where(array('status' => $status));
			return $query->num_rows();
		}

		public function set_suggestion_category($id,$category_id){
			return parent::update($id,array('category_id'=>$category_id));
		}

		public function get_suggestion_category($id) {
			$query = parent::get_where(array('id'=>$id));
			if ($query->nums_rows() > 0){
				return $query->row()->category_id;
			}
			return FALSE;
		}

		public function get_suggestion_with_slug($slug,$status = NULL) {
			if (!$this->slug_exists($slug))
				return FALSE; 

			if ($status == NULL) {
				$suggestion = parent::get_where(array('Slug' => $slug)); 
			} else {
				$suggestion = parent::get_where(array('Slug' => $slug,'Status' => $status));
			}

			$result = array(); 
			$row = $suggestion->row(); 
			return $row;
		}


		public function is_empty() {
			if ($this->num_rows() == 0)
				return TRUE; 
			return FALSE; 
		}

		public function order_by($order) {
			$this->db->order_by($order,'DESC');
			$query = $this->db->get($this->table); 
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$data[] = $row;
				}

				return $data;
			}
				return FALSE;

		}

		public function search($keyword,$limit=NULL) {
			$this->db->like('Title',$keyword,'both');
			if($limit === NULL) {
				$query = $this->db->get($this->table);
			}
			else
			{
				$query = $this->db->get($this->table,$limit);
			}
			
			return $query;
		}

		public function search_limit($keyword,$limit) {
			return $this->search($keyword,$limit);
		}

		public function get_populated_categories(){
			$suggestions = parent::get_all();
			$data = array();
			foreach ($suggestions as $suggestion){
				if ($suggestion->category_id != NULL) {
					$data[] = $suggestion->category_id;
				}
			}

			$data = array_unique($data);
			return $data;
		}

		public function count_populated_category($id){
			$query = parent::get_where(array('category_id' => $id));
			return ($query->num_rows() === 0) ? FALSE : $query->num_rows();
		}

		public function reset_category_id($cat_id){
			$query = parent::get_where(array('category_id' => $cat_id));
			if ($query->num_rows()>0){
				foreach ($query->result() as $row){
					parent::update($row->ID,array('category_id' => null));
				}
			}
		}


	}
?>