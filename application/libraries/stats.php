<?php defined('BASEPATH') or exit('No direct script access allowed');

	class CI_Stats {
		protected $CI;
		protected $lastnumber = 5;

		protected $lastlogout; 

		public function __construct(){
			$this->CI =& get_instance();
			$this->CI->load->helper('account');
			$this->get_last_co_datetime();
		}

		private function get_last_co_datetime(){
			if (is_admin_connected()){
				$currentConnectedAdmin = get_connected_user_id();
				$this->lastlogout	   =  get_lastlogout_date($currentConnectedAdmin);
				return $this->lastlogout;
			}
			return FALSE;
		}

		public function last_suggestions() {
			$this->CI->load->model('suggestion_model');
			$this->get_last_co_datetime();
			if ($this->lastlogout === FALSE || $this->lastlogout === NULL) {
				return 0;
			} 
			
			$query = $this->CI->suggestion_model->get_where(array('CreatedOn >=' => $this->lastlogout));
			return $query->num_rows();
		}

		public function last_users() {
			$this->CI->load->model('users/user_model');
			$this->get_last_co_datetime();
			if ($this->lastlogout === FALSE || $this->lastlogout === NULL) {
				return 0;
			} 
			$query = $this->CI->user_model->get_where(array('CreatedOn >=' => $this->lastlogout));
			return $query->num_rows();
		}

		public function last_comments() {
			$this->CI->load->model('comment_model');
			$this->get_last_co_datetime();
			if ($this->lastlogout === FALSE || $this->lastlogout === NULL) {
				return 0;
			} 
			$query = $this->CI->comment_model->get_where(array('AddedOn >=' => $this->lastlogout));
			return $query->num_rows();
		}


 	}


 ?>