<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 *	Auth Library 
 * 	Provides authentication functions for logging users in/out.
 *
 * @category Libraries
 *
 */

class Auth {

	/**
	*	A pointer to the CodeIgniter Instance
	*
	*	@access private
	*
	*   @var object
	*/
	private $CI; 

	/**
  	 *	Stores the IP Address of the current user
  	 *
	 *	@access private
	 *
	 *	@var string
	*/
	private $IP_Address;

	public $rules = array(

					array('field' => 'username',
						  'label' => 'Username',
						  'rules' => 'trim|required|min_length[4]|max_length[50]|is_unique[users.Username]'
					),
					array(
						  'field' => 'password',
						  'label' => 'Password',
						  'rules' => 'trim|required|min_length[4]|max_length[50]'
					),
					array(
						 'field' => 'email', 
						 'label' => 'Email',
						 'rules' => 'required|valid_email|is_unique[users.email]|max_length[32]|xss_clean'
					)
				);

	public $rules_admin = array(

					array('field' => 'username',
						  'label' => 'Username',
						  'rules' => 'trim|required|min_length[4]|max_length[50]|is_unique[users.Username]'
					),
					array(
						  'field' => 'password',
						  'label' => 'Password',
						  'rules' => 'trim|required|min_length[4]|max_length[50]|matches[password_confirm]'
					),
					array(
						 'field' => 'email', 
						 'label' => 'Email',
						 'rules' => 'required|valid_email|is_unique[users.email]|max_length[32]|xss_clean'
					),
					array(
						'field' => 'password_confirm',
						'label' => 'Confirm Password',
						'rules'	=> 'trim|matches[password]'
						)
				);


	public function __construct()
	{

		$this->CI =& get_instance(); 

		$this->IP_Address = $this->CI->input->ip_address();

		$this->CI->load->config('users/auth',TRUE); 
		$this->CI->load->library('email');

		$this->CI->load->helper('url');
		
		if(!class_exists('CI_Session'))
		{
			$this->CI->load->library('session');
		}

	}

	public function Activate($Id,$code=NULL)
	{
		if($this->CI->user_model->activate($Id,$code))
			return TRUE;
		return FALSE;
	}

	public function Deactivate($Id,$NewCode)
	{
		if($this->CI->user_model->deactivate($Id,$NewCode))
			return TRUE;
		return FALSE;
	}


	// STATUS
	// 0 => Active
	// 1 => Need Email Confirmation
	// 4 => Blocked
	public function register($newUser) {
		if (CI_Settings::get('Allow_New_Reg') == TRUE) {

			if ($this->CI->session->userdata('anonymous') != FALSE) {
				$this->CI->session->sess_destroy();
			}

			$this->CI->load->model('users/user_model');
			$newUser['SALT'] 		= $this->gen_Slat($newUser['Username']);
			$newUser['Password']	= $this->CI->user_model->hash_password($newUser['Password'],$newUser['SALT']);
			$newUser['Status']		= 0;
			if (!isset($newUser['user_role'])) {
				$newUser['user_role'] = 2;
			}

			if (CI_Settings::get('Email_Val') == TRUE) {
				$newUser['Status'] = 1; 
				$newUser['Activation_Code'] = $this->generate_code();
				$this->send_activation_email($newUser['Username'],$newUser['email'],$newUser['Activation_Code']);
			}

		

		return  $this->CI->user_model->insert_user($newUser);
		}

	return 'REGISTRATION_NOT_ALLOWED';
	}

	private function generate_code($len=16) {
		$letters = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789'; 
		$i = 0;
		$ret = ''; 
		while( $i <= $len) 
		  {
			  $ret .= $letters{mt_rand(0,$len)};
			  $i++;
		  }
		  
		return $ret;
	}

	public function resend_activation_email($username,$email,$code)
	{
		$this->CI->load->helper('user');
		$activation_code = $this->generate_code();
		$this->CI->user_model->deactivate(get_userid_by_email($email),$activation_code);

 		$data['TemplateName']    = 'activation_mail'; 
 		$data['to'] 		  	 = $email;
 		$data['activation_code'] = $activation_code;

 		$this->CI->load->library('CI_Mail','mail'); 
 		$this->CI->mail->send_email($data); 
	}

	public function send_activation_email($username,$email,$code) {
			
			$data['TemplateName'] 		  = 'Registration Email'; 
			$data['Hooks']				  = array("SEARCH" 	 => array('{username}','{link}'),
								  			      "SUBJECTS" => array($username,$this->get_activation_link($username,$code)));
			$data['to']			  		  = $email; 

			$this->CI->load->library('mail'); 
			$this->CI->mail->send_email($data); 
	
	}

	private function get_activation_link($username,$code) {
		return site_url().'/user/activate/account/'.$username.'/'.$code;
	}

	/**
	 *	Attempt to log the user in.
	 *
	 *	@access public
	 *  @param string login The user (username)
	 *  @param bool  $remember use Cookie authentication
	 */
	public function login($login,$password,$rememberMe=FALSE)
	{

		if(empty($login) || empty($password))
			return FALSE;
		

		if ($this->CI->session->userdata('anonymous') != FALSE) {
			$this->CI->session->unset_userdata('anonymous');
		}
		

		$this->CI->load->model('users/user_model');
		$this->CI->load->helper('email');

		if (valid_email($login))  {
			if ($this->CI->user_model->login($login,$password,$rememberMe,TRUE)) {
				$isAdmin = $this->CI->user_model->isAdmin_by_email($login); 
				if (!$isAdmin) {
					$status = $this->CI->user_model->getUserStatus($login,TRUE); 

					if ($status == 4) {
						return 'ACCOUNT_BLOCKED';
					}

					if ($status != 0) {
						return 'NEED_MAIL_ACTIVATION';
					}

					$this->SetUserLogged($this->CI->user_model->get_userid_by_email($login),$isAdmin);
					return TRUE;
					
				}
				else {
					$this->SetUserLogged($this->CI->user_model->get_userid_by_email($login),$isAdmin);
					return TRUE;
				}

				return FALSE;

			}
		}	
		else {

				if($this->CI->user_model->login($login,$password,$rememberMe))
				{
					$isAdmin = $this->CI->user_model->isAdmin($login);
					
					if (!$isAdmin) {

						$status = $this->CI->user_model->getUserStatus($login);

						if ($status == 4) {
							return 'ACCOUNT_BLOCKED';
						}

					
						if ($status != 0) {
							return 'NEED_MAIL_ACTIVATION';
						}


					}	
					$this->SetUserLogged($this->CI->user_model->get_User_Id($login),$isAdmin); 			
					return TRUE;
				}

				return FALSE;

			}

			//$this->__check_anonymous_activity();
			
	}

	// Reserved for Next Version
	/*private function __check_anonymous_activity() {
		if ($this->session->userdata('anonymous') != FALSE) {
				$this->session->unset_userdata('anonymous');

				// Fire Events
		}
	}*/

	public function get_associated_id($id,$type) {
		if ($type == 'facebook') {
			$id_label = 'fb_user_id'; 
		} elseif ($type == 'twitter') {
			$id_label = 'twitter_id'; 
		}

		$this->CI->load->model('users/profile_user'); 
		return $this->CI->profile_user->get_associated_id($id,$id_label);

	}

	public function login_via_facebook($user) {
		// 
		$user_id = $this->get_associated_id($user['id'],'facebook');
		$status = $this->CI->user_model->get_user_status_by_id($user_id);

		if ($status == 4) {
			return 'ACCOUNT_BLOCKED';
		}

		$this->SetUserLogged($user_id,FALSE,'facebook');
		return TRUE;
	}

	public function login_via_twitter($user) {
		$user_id = $this->get_associated_id($user['id'],'twitter');

		$status = $this->CI->user_model->get_user_status_by_id($user_id);

		if ($status == 4) {
			return 'ACCOUNT_BLOCKED';
		}

		$this->SetUserLogged($user_id,FALSE,'twitter');
		return TRUE;
	}

	// Return username or his/her profile name
	public function get_user_name($id) {
		if ($id === 0) {
			return "Anonymous";
		}

		$this->CI->load->model('users/user_model'); 
		$username = $this->CI->user_model->get_username_by_id($id); 
		if ( $username != FALSE) 
			return $username;

		$this->CI->load->model('users/profile_user'); 
		return 	$this->CI->profile_user->get_user_name($id);
	}


	// Create a new user & return his/her ID
	private function so_new_user($user) {

		if ($this->CI->user_model->get_userid_by_email($user['email']) != FALSE) 
			return $this->CI->user_model->get_userid_by_email($user['email']);

		$this->CI->load->model('users/user_model'); 
		$this->CI->user_model->insert_user(array(
				'email' 	=> $user['email'],
				'Username'  => '',
				'Password'  => '', 
				'user_role' => 2,
				'Status'	=> 0, 
				'Activation_Code' => ''
			));

		return $this->CI->user_model->get_userid_by_email($user['email']);
	}

	public function sign_up_from_social($user,$social) {

		// Create new Profile
		// Create new entry in users table without username & password

		$this->CI->load->model('users/profile_user'); 

		if ($social == 'facebook') {
			$input['fb_user_id'] = $user['id'];
		} else if ($social == 'twitter') {
			$input['twitter_id'] = $user['id'];
		}

		$user_id = $this->so_new_user($user);
		$input = array_merge($input,array('name' => $user['name'],'email' => $user['email'],'user_id' => $user_id));


		return $this->CI->profile_user->insert_profile($input);

	}


	public function logout()
	{	
		
		$last_connected_id = get_connected_user_id();
		$this->CI->user_model->set_logoutdate($last_connected_id);
		if (CI_Settings::get('FacebookLogin')) {
			$this->CI->load->library('fb_ignited');
			$this->CI->fb_ignited->destroySession();
		}
		
		$this->CI->session->sess_destroy();		
		unset($_SESSION);
		return TRUE;
	}

	public function is_logged_in()
	{

		$is_logged_in = $this->CI->session->userdata('logged_in');
		if ((isset($is_logged_in) && $is_logged_in == TRUE))
		{
			return TRUE;
		}
		return FALSE;
	}

	public function SetUserLogged($user_id,$Isadmin=FALSE,$social=FALSE)
	{

		if (CI_Settings::get('AnonymousPosting')) {
			if ($user_id === 0) {
					$newSession = array(
							'user_id'   => 0,
							'anonymous' => TRUE,
							'logged_in' => TRUE,
							'Isadmin'	=> FALSE
					);

					$this->CI->session->set_userdata($newSession); 
					return TRUE;
				}
		}

		if ($Isadmin == TRUE) {
			$Newsession = array('user_id'   => $user_id, 
								'Isadmin'   => TRUE, 
								'logged_in' => TRUE);
			if ($social != FALSE) {
				array_merge($Newsession,array('with_so' => $social));
			}
		}
		else
		{
			$Newsession = array('user_id'  => $user_id, 
								'logged_in' => TRUE);
			if ($social != FALSE) {
				array_merge($Newsession,array('with_so' => $social));
			}
		}	

		$this->CI->session->set_userdata($Newsession); 
		$this->CI->user_model->update_last_loginByUser($user_id);

		return TRUE;
	}

	public function Todashboard($new=FALSE)
	{	

		$Isadmin = $this->CI->session->userdata('Isadmin');
			
			if (!$new) { 
			
				if ($Isadmin)
				{
					redirect('admin');
				}
				else
				{
					redirect('user');
				}

			}
			else
			{

				if ($Isadmin)
				{
					redirect('admin/newUser');
				}
				else
				{
					redirect('user/newUser');
				}


			}	
		
	}
	public function Delete_User($Id)
	{
		if($this->CI->user_model->delete_user($Id))
		{
			$this->set_message('DELETE_SUCCESS');
			return TRUE; 
		}
		$this->set_error('DELETE_ERROR');
		return FALSE;
	}

	public function Username_check($username)
	{
		return $this->CI->user_model->Username_check($username);
	}

	public function email_exists($email)
	{
		return $this->CI->user_model->email_exists($email);
	}

	public function fb_user_exists($id) {
		$this->CI->load->model('users/profile_user'); 
		return $this->CI->profile_user->record_exists(array('fb_user_id' => $id));
	}

	public function update_password($id,$password) {
		$username = $this->CI->user_model->get_username_by_id($id);
		$salt 	  = $this->gen_Slat($username);
		$this->CI->user_model->save_salt($id,$salt);
		$password = $this->CI->user_model->hash_password($password,$salt);
		$this->CI->user_model->set_password($id,$password);
		return TRUE;
	}

	public function gen_Slat($username) {
		$strs = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 

		$i = 0; 
		$return = '';
		while ( $i < 5) {
			$return .= $strs[rand(0,61)];
			$i++;
		}

		$return = $return . $username . strrev($return);
		return $return;
	}


	private function Create_autologin($UserID)
	{
		$this->CI->load->helper('string');
		$token = rand_string('alum',128);


		$data = array(
			'UserID'     => $UserID,
			'Token'	     => $token,
			'Created_On' => date('Y-m-d H:i:s'),
			'IP_Address' => $this->CI->input->ip_address()
		);

		$this->CI->db->insert('user_cookies',$data);

		if ($this->CI->db->affected_rows())
		{
			$this->CI->input->set_cookie('autologin', $UserID.'&'.$token);

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function block_user($id) {
		return $this->CI->user_model->block($id);
	}

	public function unblock_user($id) {
		return $this->CI->user_model->unblock($id);
	}

	private function Delete_autologin()
	{
			$this->CI->load->helper('cookie');
			$cookie = get_cookie('autologin');
			
			if($cookie)
			{
				list($User_ID,$token) = explode('&',$cookie);
				delete_cookie('autologin');
				$this->CI->db->where('UserID',$UserID);
				$this->CI->db->where('Token',$token);
				$this->CI->db->delete('Cookie_Sessions');
			}

		$this->CI->db->where('CreatedOn', '< DATE_SUB(CURDATE(), INTERVAL 2 MONTH)');
		$this->CI->db->delete('Cookie_Sessions');
	}

	public function check_user_status($userId) {
		$status = $this->user_model->get_user_status($userId);
	}
}
?>