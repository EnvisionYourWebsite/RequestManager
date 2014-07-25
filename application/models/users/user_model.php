<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class User_model extends MY_Model {

		protected $table = 'users';
		public function __construct() {
			parent::__construct(); 
			$this->set_table_name($this->table);
			$this->set_primary_key('ID');

		}

		public function insert_user($input) {
			$input['CreatedOn'] = date('Y-m-d H:i:s');
			$input['LastIP']    = $this->input->ip_address();
			$input['LastLogin']	= time();
			return parent::insert($input);
		}

		public function login($login,$password,$rememberMe,$email=FALSE)
		{	
			if($email) {
				$salt = $this->get_salt_by_email($login);
				$query = parent::get_where(array('email' => $login,'Password' => $this->hash_password($password,$salt))); 

			} else {
				$salt = $this->get_salt_by_username($login);
				$query = parent::get_where(array('Username' => $login,'Password' => $this->hash_password($password,$salt)));
			}
			
			if($query->num_rows() == 1)
				return TRUE;		
			return FALSE;
		}

		public function get_salt_by_username($username) {
			$query = parent::get_where(array('Username' => $username));
			if ($query->num_rows() > 0) {
				return $query->row()->SALT;
			}
			return FALSE;
		}

		public function get_salt_by_email($email) {
			$query = parent::get_where(array('email' => $email));
			if ($query->num_rows() > 0) {
				return $query->row()->SALT;
			}
			return FALSE;
		}

		public function user_exists($Id) {
			return parent::record_exists(array($this->get_primary_key() => $Id));
		}

		public function username_exists($username) {
			return parent::record_exists(array('Username' => $username));
		}

		public function get_User_Id($Username) {
			$query = parent::get_where(array('Username' => $Username));
			if ($query->num_rows() > 0) {
				return $query->row()->ID;
			}
			return FALSE;
		}

		public function get_userid_by_email($email) {
			$query = parent::get_where(array('email' => $email));
			if ($query->num_rows() > 0) {
				return $query->row()->ID;
			}
			return FALSE;
		}
		public function get_username_by_email($email) {
			$query = parent::get_where(array('email' => $email));
			if ($query->num_rows() > 0) {
				return $query->row()->Username;
			}
			return FALSE;
		}
		public function get_username_by_id($id) {
			$query = parent::get_where(array('ID' => $id)); 
			if ($query->num_rows() > 0) {
				return $query->row()->Username;
			}
			return FALSE; 
		}

		public function update_last_loginbyId($Id) {
			return parent::update($Id,array('LastLogin' => date('Y-m-d H:i:s'),'LastIP' => $this->input->ip_address()));
		}

		public function email_exists($email) {
			return parent::record_exists(array('email' => $email));
		}

		public function change_username($id,$username) {
			if ($username == NULL)
				return;
			
			return parent::update($id,array('Username' => $username));
		}

		public function update_last_loginByUser($user_id) {
			return $this->db->where('ID',$user_id)
								->set(array('LastLogin' => date('Y-m-d H:i:s'),'LastIP' => $this->input->ip_address()))
								->update($this->table);
		}

		public function activate($Id) {
			return parent::update($Id,array('Status' => 0,'Activation_Code' => ''));
		}

		public function deactivate($Id,$newCode) {
			return parent::update($Id,array('Status' => 1,'Activation_Code' => $newCode));
		}

		public function block($ID) {
			return $this->db->where('ID',$ID)
							->set(array('Status' => 4))
							->update($this->table);
		}

		public function unblock($Id) {
			return $this->db->where('ID',$Id)
							->set(array('Status' => 0))
							->update($this->table);
		}

		public function admin_activation($User_ID)
		{

			$query = $this->db
						  ->select('ID')
						  ->where('ID',$User_ID)
						  ->limit(1)
						  ->get($this->table);

			if($query->num_row() != 1)
			{
				return FALSE;
			}

			$result = $query->row();


		}

		public function hash_password($password,$salt) {
			return sha1($password.$salt);
		}

		public function count_inactive_users()
		{
			$this->db->where('Status',0);
			return $this->count_all(FALSE);
		}

		// 0 => Super Admin
		// 1 => Admin 
		// 2 => User	
		public function isAdmin($Username)
		{
			$query  = $this->db->get_where($this->table,array('Username' => $Username),1);

			if ($query->num_rows() > 0) {
				if ($query->row()->user_role == 0 || $query->row()->user_role == 1)
					return TRUE;
			}
			return FALSE; 
		}

		public function isAdmin_by_email($email) {
			$query = $this->db->get_where($this->table,array('email' => $email),1); 
			if ($query->row()->user_role == 0 || $query->row()->user_role == 1) {
				return TRUE;
			}
			return FALSE; 
		}

		public function is_admin_by_id($userId) {
			$query  = $this->db->get_where($this->table,array('ID' => $userId),1);
			if ($query->num_rows() > 0) {
				if ($query->row()->user_role == 0 || $query->row()->user_role == 1)
					return TRUE;
			}
			return FALSE; 
		}


		// 0 => Active
		// 1 => Need Email Confirmation
		// 2 => Blocked
		public function getUserStatus($username,$email=FALSE) {

			if ($email) {
				$query = $this->get_where(array('email' => $username)); 
			} else {
				$query = $this->get_where(array('Username' => $username));
			}
			if ($query->num_rows > 0) {
				return $query->row()->Status;
			}
			return FALSE;
		}

		public function get_user_status_by_id($id) {
			$query = $this->get_where(array('ID' => $id)); 
			if ($query->num_rows() > 0) {
				return $query->row()->Status;
			}
			return FALSE; 
		}

		public function set_admin_rights($userId) {
			return parent::update($userId,array('user_role' => 1));
		}
		public function remove_admin_rights($userId) {
			return parent::update($userId,array('user_role' => 2));
		}

		public function get_users_only() {
			$this->db->where('user_role !=','0'); 
			$this->db->where('user_role !=','1'); 
			$this->db->from($this->table); 

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return $query->result_array();
			}
			return FALSE;
		}


		// Get Users and Admin Expect Super Admin
		public function get_users_and_admins() {
			$query = parent::get_where(array('user_role !=' => 0)); 
			if ($query->num_rows() > 0) {
				return $query->result(); 
			}
			return FALSE; 
		}

		public function num_users() {
			$this->db->where('user_role !=',0); 
			$this->db->where('user_role !=',1); 
			$this->db->from($this->table); 
			$query = $this->db->get();
			return $query->num_rows();
		}


		public function num_users_and_admins() {
			$query = $this->db->query('SELECT * FROM '. $this->table . ' WHERE user_role != 0');
			return $query->num_rows();
		}

		public function count_blocked_users(){
			$query = parent::get_where(array('status !=' => 0));
			return $query->num_rows();
		}

		public function set_username($id,$username) {
			return parent::update($id,array('Username' => $username));
		}

		public function set_password($id,$password) {
			return parent::update($id,array('password' => $password));
		}

		public function save_salt($id,$salt) {
			return parent::update($id,array('SALT' => $salt));
		}

		public function set_logoutdate($id) {
			return parent::update($id,array('Lastlogout' => date('Y-m-d H:i:s')));
		}	 

		public function get_lastlogout($id) {
			$query = parent::get_where(array('ID' => $id));
			if ($query->num_rows() > 0)
				return $query->row()->Lastlogout;
			return FALSE;
		}
	}
?>