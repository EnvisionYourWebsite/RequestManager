<?php 

	if (!function_exists('remove_account')) {
		function remove_account($id) {
				// Remove User 
				// Remove User Profile
				// Remove Ideas
				// Remove Votes 
				// Remove Comments 
			$CI =& get_instance();
			$CI->load->model('suggestion_model'); 
			$CI->load->model('users/user_model');
			$CI->load->model('comment_model');
			$CI->load->model('rating/rating_model');
			$CI->load->model('users/profile_user');
			$CI->user_model->delete($id); 
			$CI->profile_user->remove_user_profile_id($id);
			$CI->suggestion_model->remove_user_suggestions($id);
			$CI->rating_model->remove_user_votes($id);
			$CI->comment_model->remove_user_comments($id);

		}
	}

	function init() {
		$CI=& get_instance();
		$CI->load->model('users/user_model'); 
		return $CI;
	}

	if(!function_exists('generate_username')) {
		function generate_username($email) {
			$CI = init();
			$parts = explode('@',$email);
			$username = $parts[0]; 
			if ($CI->user_model->username_exists($username)) {
				$username .= substr(uniqid(),0,4);
			}
			return $username;
		}
	}

	if (!function_exists('create_new_account')) {
		function create_new_account($email,$password) {
			$CI =& get_instance();
			$CI->load->library('users/auth'); 

			$username = generate_username($email); 

			$new_generated_user_info = array('Username'  => $username,
											 'Password'  => $password,
											 'email' 	 => $email,
											 'LastIP' 	 => $CI->input->ip_address(),
											 'user_role' => 2);

			return $CI->auth->register($new_generated_user_info);
		}
	}

	if (!function_exists('get_profile_name')) {

		function get_profile_name($id) {
			$CI =& get_instance(); 
			$CI->load->model('users/profile_user'); 
			return $CI->profile_user->get_user_name($id);
		}

	}
	

	if (!function_exists('get_user_id')) {
		function get_user_id($username) {
			if ($username == FALSE)
				return FALSE;
			$CI = init();
			return $CI->user_model->get_User_Id($username);
		}
	}

	if (!function_exists('get_username_byId')) {
		function get_username_byId($UserID) {
			$CI = init();
			return $CI->user_model->get_username_by_id($UserID); 
		}
	}
		
	if (!function_exists('get_userid_by_email')) {
		function get_userid_by_email($email) {
			$CI = init();
			return $CI->user_model->get_userid_by_email($email);
		}
	}

	if (!function_exists('get_username_byemail')) {
		function get_username_byemail($email) {
			$CI = init();
			return $CI->user_model->get_username_by_email($email);
		}
	}

	if (!function_exists('get_connected_username')) {
		function get_connected_username() {
			$CI =& get_instance(); 
			$CI->load->library('users/auth');
			$user_id = get_connected_user_id();
			if ($user_id != FALSE) {
				return $CI->auth->get_user_name($user_id);
			}
			return FALSE;
		}
	}

	if (!function_exists('get_connected_user_id')) {
		function get_connected_user_id() {
			$CI =& get_instance(); 
			$user_id = FALSE;
			if ($CI->session->userdata('user_id')) {
				$user_id = $CI->session->userdata('user_id'); 
			}
			return $user_id;
		}
	}

	if (!function_exists('get_author_byId')) {
		function get_author_byId($id) {
			if ($id == 0 || $id == null)
				return "Anonymous";

			$CI = init();
 			$username = $CI->user_model->get_username_by_id($id);
			if ($username != FALSE)
				return $username; 

			$CI->load->model('users/profile_user'); 
			return $CI->profile_user->get_user_name($id);
		}
	}

	if (!function_exists('is_admin_connected')) {
		function is_admin_connected() {
			$CI = init();
			return $CI->user_model->is_admin_by_id(get_connected_user_id());
		}
	}

	if (!function_exists('is_user_logged_in')) {
		function is_user_logged_in() {
			$CI =& get_instance(); 
			$is_logged_in = $CI->session->userdata('logged_in');
			if (isset($is_logged_in) && $is_logged_in == TRUE)
				return TRUE;
			return FALSE;
		}
	}

	if (!function_exists('is_anonymous_logged_in')) {
		function is_anonymous_logged_in() {
			$CI =& get_instance(); 
			$is_anonymous_logged_in = $CI->session->userdata('anonymous');
			if (isset($is_anonymous_logged_in) && $is_anonymous_logged_in == TRUE)
				return TRUE;
			return FALSE;
		}
	}

	if (!function_exists('get_user_votes_left')) {
		function get_user_votes_left($username) {
			$CI =& get_instance(); 
			$CI->db->where(array('UserId' => get_user_id($username))); 
			$query = $CI->db->get('users_info'); 
			if ($query->num_rows() > 0) 
				return $query->row()->votesleft;
			return CI_Settings::get('Max_votes');
		}
	}

	if (!function_exists('get_user_votes_left_by_id')) {
		function get_user_votes_left_by_id($id) {
			$CI =& get_instance(); 
			$CI->db->where(array('UserId' => $id)); 
			$query = $CI->db->get('users_info'); 
			if ($query->num_rows() > 0) 
				return $query->row()->votesleft;
			return CI_Settings::get('Max_votes');
		}
	}

	if( !function_exists('has_current_user_suggested_ideas')) {
		function has_current_user_suggested_ideas() {
			$CI =& get_instance(); 
			$userID =  get_connected_user_id();
			$CI->db->where(array('UserID' => $userID)); 
			$query = $CI->db->get('suggestions'); 
			if ($query->num_rows() > 0)
				return TRUE;
			return FALSE; 
		}
	}

	if (!function_exists('check_setting')) {
		function check_setting($setting,$username=NULL) {
			if ($username == NULL) {
				$userid =  get_connected_user_id();
			}
			else {
				$userid = get_user_id($username); 
			}

			$CI =& get_instance(); 
			$CI->db->where(array('UserID' => $userid)); 
			$query = $CI->db->get('user_settings'); 
			if ($query->num_rows() > 0) {
				if ($query->row()->$setting == 'TRUE')
					return TRUE;
			}
			return FALSE; 
		}
	}

	if (!function_exists('count_user_votes')) {
		function count_user_votes($userId) {
			$CI =& get_instance(); 
			$CI->db->where(array('UserID' => $userId)); 
			$query = $CI->db->get('votes_log');
			$votes = NULL;
			if ($query->num_rows() > 0) {
				foreach ($query->result() as $row) {
					$votes += $row->Votes;
				}
			}	
			return $votes;
		}
	}

	if (!function_exists('is_admin')) {
		function is_admin($userId) {
			$CI = init();
			return $CI->user_model->is_admin_by_id($userId);
		}
	}

	
	if (!function_exists('get_user_status')) {
		function get_user_status($username) {
			$CI = init(); 
			return $CI->user_model->getUserStatus($username);
		}
	}

	if (!function_exists('get_only_sucribbed_users')) {
		function get_only_subscribed_users($setting,$users) {
			if (empty($users)) {
				return FALSE;
			}

			if(!is_array($users)) {
				$users[] = $users;
			}

			$_users = NULL;

			if(!is_object($users) && ! is_array($users)) {
				$users = (object) $users;
				$USERS[] = $users;
				$users = $USERS; 
			}
			
			foreach($users as $user) {
				if (is_array($user)) {
					if (check_setting($setting,$user['Username'])) {
					$_users[] = $user;
					} 
				}
				else if (is_object($user)) {
					if (check_setting($setting,$user->Username)) {
						$_users[] = $user; 
					}
				}
				
				
			}
			
			return $_users;
		}
	}

	if(!function_exists('get_lastlogin_date')) {
		function get_lastlogout_date($id) {
			$CI = init();
			return $CI->user_model->get_lastlogout($id);
		}
	}

?>