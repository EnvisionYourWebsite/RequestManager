<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Category_model extends MY_Model {
		protected $table = "categories";

		public function __construct() {
			parent::__construct();
			$this->set_table_name($this->table);
			$this->set_primary_key('id');
		}

		private function generate_slug($title) {
	          $slug = strtolower($title);
	               
	          $slug = preg_replace("/[^a-z0-9\s-]/", "", $slug);
	          $slug = trim(preg_replace("/[\s-]+/", " ", $slug));
	          $slug = preg_replace("/\s/", "-", $slug);

	          return $slug;
	       
		}

		public function category_exsits($category_name=NULL) {
			if ($category_name === NULL) 
				return parent::num_rows();
			
			$query = parent::get_where(array('category_name'=> $category_name));
			if ($query->num_rows()>0)
				return TRUE;
			return FALSE;
		}

		public function get_category_id($category_name) {
			$qurey = parent::get_where(array('category_name' => $category_name));
			if ($qurey->num_rows()>0) {
				return $query->row()->id;
			}
			return FALSE;
		}

		public function get_category_id_by_slug($category_slug){
			$query = parent::get_where(array('category_slug' => $category_slug));
			if ($query->num_rows()>0){
				return $query->row()->id;
			}
			return FALSE;
		}

		public function get_category_name($id) {
			$query = parent::get_where(array('id' => $id));
			if ($query->num_rows()>0){
				return $query->row()->category_name;
			}
			return FALSE;
		}

		public function add_new_category($category_name) {
			if ($this->category_exsits($category_name))
				return FALSE;
			$slug = $this->generate_slug($category_name);
			return parent::insert(array('category_name' => $category_name,'category_slug' => $slug));
		}

		public function remove_category($id) {
			// Change suggestion to other categories
			return parent::delete($id);
		}

		public function get_category_slug($id) {
			$query = parent::get_where(array('id'=> $id));
			if ($query->num_rows() > 0) {
				return $query->row()->category_slug;
			}
			return FALSE;
		}

		private function __update_slug($id,$category_name) {
			return parent::update($id,array('category_slug',$this->generate_slug($category_name)));
		}

		public function rename_category($id,$new_name) {
			$return = parent::update($id,array('category_name',$new_name));
			return ($return && $this->__update_slug($id,$new_name));
		}

		public function get_categories() {
			return parent::get_all();
		} 
	}
?>