<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	require_once(APPPATH.'controllers/suggest.php');
	
	class Login extends Public_Controller {
		protected $data = NULL;
		public function __construct() {
			parent::__construct();

			$this->load->helper('account'); 
			$this->load->helper('form');
			$this->load->library('users/auth'); 
			if (CI_Settings::get('FacebookLogin')) {
				$this->load->library('Fb_ignited');
			}
			
			if ($this->auth->is_logged_in() && (!is_anonymous_logged_in())) {
				redirect('/'); 
			}
		}

		public function index(){

			$this->template
				 ->set_page_title('Login')
				 ->add_css_file('form.css')
				 ->set_view('login');

			if (!$this->data == NULL) {
				$this->template->set_additional_data($this->data);
			}

			$this->template->render();
		}

		public function authenticate() {

			$this->load->library('form_validation'); 

			$email = $this->input->post('username');

			$this->load->helper('email'); 
			if (valid_email($this->input->post('username'))) {
				$this->validation_rules = array(	
					array(
						'field' => 'username',
						'label' => 'Email',
						'rules' => 'required|xss_clean'
					),
					array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'required'
					)
				);

			} 
			else 
			{

				$this->validation_rules = array(	
					array(
						'field' => 'username',
						'label' => 'Username',
						'rules' => 'required'
					),
					array(
						'field' => 'password',
						'label' => 'Password',
						'rules' => 'required'
					)
				);
			}

		$this->form_validation->set_rules($this->validation_rules);

		if ($this->form_validation->run()) {

			$result = $this->__authenticate(); 
			if (is_bool($result) && $result == TRUE) {
				redirect('home');
			}
			elseif (is_array($result) && array_key_exists('error', $result)) {
				$this->data['error'] = $result['error'];
				$this->index();
			}
			
		}
		else {
			$this->index();
		}

		
		}

		private function __authenticate() {

			$login = $this->input->post('username');
			
			if (!$this->input->post('username')) {
				$login = $this->input->post('email');
			}
			$result = $this->auth->login($login,$this->input->post('password'));
			if (is_bool($result) && $result == TRUE) {
				$data = TRUE;
			}
			elseif (is_string($result) && $result == 'NEED_MAIL_ACTIVATION') {
				$data['error'] = lang('activate_your_account'); 
			}
			elseif (is_string($result) && $result == 'ACCOUNT_BLOCKED') {
				$data['error'] = lang('account_blocked'); 
			}
			else {
				$data['error'] = lang('incorrect_username_email_password');
			}

			return $data;

		}

		public function log_as_anonymous() {
			if (CI_Settings::get('AnonymousPosting') === TRUE) {
			
				$this->auth->SetUserLogged(0); 
				echo json_encode(array('status' => 2,'msg' => "<p class=\"alert alert-success\">" .lang('logged_as_anonymous_msg') . "</p>", 'as' => 'anonymous'));
			}
			else
			{
				echo json_encode(array('status' => 0));
			}
		}

		public function authenticate_ajax(){
			if ($this->input->is_ajax_request()) {
				
				$this->load->library('form_validation'); 
				$this->validation_rules = array(	
						array(
							'field' => 'email',
							'label' => 'Email',
							'rules' => 'required'
						),
						array(
							'field' => 'password',
							'label' => 'Password',
							'rules' => 'required'
						)
					);

				$this->form_validation->set_rules($this->validation_rules);

				if ($this->form_validation->run()) {

					 $result = 	$this->__authenticate();

					 if (is_array($result)) {
					 	echo json_encode(array('status' => 0,'msg' => "<p class=\"alert alert-danger\">" .  $result['error'] . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
					 }
					 elseif (is_bool($result) && $result == TRUE) {
						$data['is_user_logged_in'] = TRUE;
							if (is_admin_connected()) {
								$data['isAdmin'] = TRUE;
								$data['nav_selected'] = 'Home';							}
						$nav = $this->load->view('fragment/main_nav_bar',$data,TRUE);
						echo json_encode(
							array('status' => 'logged_in',
								  'msg'	   => "<p class=\"alert alert-success\">" . lang('loggedin_success') . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>",
								  'html'   => $nav
								 ));
					 }
					 elseif (is_bool($result) && $result == FALSE) {
					 	echo json_encode(array('status' => 'failtolog','msg' => "<p class=\"alert alert-danger\">" . lang('incorrect_email_password') .  "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
					 }

				}

			}
		}
	

		public function is_email_exists() {
			$this->load->helper('email');
			if ($this->input->is_ajax_request()) {
				if ($this->input->post('email')) {
						$this->load->model('users/user_model'); 
					 if ($this->user_model->email_exists($this->input->post('email'))) {
					 	echo json_encode(array('status' => 1,'msg' => lang('login_now')));
					 }
					 else {
					 	if ((int)CI_Settings::get('Allow_New_Reg')==0) {
					 		$msg =  '<strong>'.lang('sorry').'</strong> ' . lang('reg_closed');
					 	}elseif ((int)valid_email($this->input->post('email')) ==0){
					 		$msg = lang('incorrect_email');
					 	}
					 	else {
					 		$msg = lang('register_now');
					 	}
					 	echo json_encode(array('status' => 0,'msg' => $msg , 'registrationAllow' => (int)CI_Settings::get('Allow_New_Reg'),'valid_email' => (int)valid_email($this->input->post('email'))));
					 }
				}
			}
		}

		public function facebook() {
			if (CI_Settings::get('FacebookLogin') == FALSE) 
				redirect('login');
			
			redirect($this->fb_ignited->fb_login_url(array('redirect' => site_url() . '/login/fb_auth')));
		}

		public function fb_auth() {
			if (CI_Settings::get('FacebookLogin') == FALSE) 
					redirect('login');

			$this->load->model('users/user_model');
			try {
				 $this->fb_me = $this->fb_ignited->fb_get_me();
			} catch (FBIgnitedException $e) {
				   redirect('login');
				}
			if ($this->fb_me) {
				   $profile = $this->fb_me;
				   if ($this->auth->fb_user_exists($profile['id'])) {
						$this->auth->login_via_facebook($profile);
						redirect('/');
					}
					else {
						$this->auth->sign_up_from_social($profile,'facebook'); 
						$this->auth->login_via_facebook($profile); 
						redirect('/');
					}

			}
			else 
			{
				redirect('login');
			}
		}

	}

?>