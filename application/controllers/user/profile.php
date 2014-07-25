<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	class Profile extends AuthenticatedUser_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->model('users/profile_user');
			$this->load->model('users/user_model');	
			$this->load->helper('account');	  

			$this->id = get_connected_user_id();	
		}

		public function index() {
			$this->template->set_view('user/profile')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css')
						   ->set_page_title('Profile'); 

			
			$profile_user = $this->profile_user->get_user_id_profile($this->id);
			if ($profile_user) {
				$profile_user->id = $this->id;
			    $this->data['results'] = $profile_user;
			}
			$this->data['nav_selected'] = 'Profile';
			$this->template->set_additional_data($this->data);
			$this->template->render();
		}	

		public function save_basics() {
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
			$this->validation_rules =  array(
					array(
						'field' 	=> "username", 
						'label'		=> 'Username', 
						'rules'		=> 'required|min_length[4]|max_length[10]|xss_clean|is_unique[users.Username]'
					));
			$this->form_validation->set_rules($this->validation_rules);
			if ($this->form_validation->run()) {
				$this->user_model->set_username($this->id,$this->input->post('username'));
				echo json_encode(array('status' => 1,'msg' => 'Settings Saved!'));
			}
			else 
			{
				echo json_encode(array('status' => 0, 'msg' => validation_errors()));
			}
		}

		public function create_password() {

			if ($this->user_model->get_username_by_id($this->id) == FALSE)
			{	

				echo json_encode(array('status' => 0,'msg' => '<p class="alert alert-danger">' . lang('need_username') . '</p>'));
				exit();
			}

			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="alert alert-danger">', '</p>');
			$this->validation_rules =  array(
					array(
						'field' 	=> "password", 
						'label'		=> 'Password', 
						'rules'		=> 'required|min_length[4]|max_length[10]'
					),
					array(
						'field'		=> 'passagain',	
						'label'		=> 'Re-enter new password',
						'rules'		=> 'required|matches[password]'
					));
			$this->form_validation->set_rules($this->validation_rules);
			if ($this->form_validation->run()) {

				// gen Salt 
				// hash pass
				// save salt and password

				$this->load->library('users/auth');
				$salt = $this->auth->gen_Slat(trim(get_author_byId($this->id)));
				$hash = $this->user_model->hash_password($this->input->post('password'),$salt); 
				$this->user_model->save_salt($this->id,$salt);
				$this->user_model->set_password($this->id,$hash);
				$msg = lang('settings_saved');
				echo json_encode(array('status' => 1,'msg' => $msg));
			}
			else 
			{
				echo json_encode(array('status' => 0, 'msg' => validation_errors()));
			}
		}
	}

?>