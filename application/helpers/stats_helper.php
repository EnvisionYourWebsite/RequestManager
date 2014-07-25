<?php
		
	if (!function_exists('count_new_suggestion')) {
		function count_last_suggestions() {
			$ci =& get_instance();
			$ci->load->library('stats');
			return $ci->stats->last_suggestions();
		}
	}

	if (!function_exists('count_new_comment')) {
		function count_last_comments() {
			$ci =& get_instance(); 
			$ci->load->library('stats');
			return $ci->stats->last_comments();
		}
	}

	if(!function_exists('count_new_users')) {
		function count_new_users(){
			$ci =& get_instance();
			$ci->load->library('stats');
			return $ci->stats->last_users();
		}
	}

	if (!function_exists('count_total_suggestion')) {
		function count_total_suggestion() {
			$ci =& get_instance(); 
			$query = $ci->db->get('suggestions'); 
			return $query->num_rows();
		}
	}

	if (!function_exists('count_total_users')) {
		function count_total_users() {
			$ci =& get_instance(); 
			$query = $ci->db->get('users'); 
			return $query->num_rows();
		}
	}


	if (!function_exists('count_suggestions_by_status')) {
		function count_suggestions_by_status($status) {
			$ci =& get_instance();
			$ci->load->model('suggestion_model');
			return $ci->suggestion_model->count_suggestions_by_status($status);
		}
	}

	function count_blocked_users(){
		$ci =& get_instance();
		$ci->load->model('user_model');
		return $ci->user_model->count_blocked_users();
	}



?>
