<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Register extends Public_Controller {
		public function __construct() {
			parent::__construct();
		}

		public function index(){
			$this->template
				 ->set_page_title('Register')
				 ->add_css_file('form.css')
				 ->set_view('register');

			$this->template->render();
		}

		public function new_member() {
			if ($this->input->post()) {
				$this->load->library('form_validation'); 
				$this->load->library('users/auth');
				$this->validation_rules = $this->auth->rules;

				$this->form_validation->set_rules($this->validation_rules);

			if ($this->form_validation->run()) {

				$newuser = array(
					'Username' => $this->input->post('username',TRUE),
					'email'	   => $this->input->post('email',TRUE), 
					'Password' => $this->input->post('password',TRUE)
					); 

				if ($this->auth->register($newuser) > 0) {
					$this->load->library('notifier');
					$data = array('action' => 'new_registration', 'username' => $newuser['Username'],'email' => $newuser['email']); 
					Events::trigger('notify',$data,'array');
					//redirect to 
					if(CI_Settings::get('Email_Val') == TRUE) {
						$this->template
						 ->set_page_title('Register')
						 ->add_css_file('form.css')
						 ->set_view('register');

						 $data['infos'] = lang('activate_your_account');
						 $this->template->set_additional_data($data); 
						 $this->template->render(); 
					}
					else {
						$this->auth->login($this->input->post('email'),$this->input->post('password'));
						redirect('dashboard');
					}
						
						
				}
				else {
					// Error
					$this->template
						 ->set_page_title('Register')
						 ->add_css_file('form.css')
						 ->set_view('register');
					$data['errors'] = validation_errors();
					
					$this->template->set_additional_data($data);	 
					$this->template->render();
				}
			}
			else {
				
				$this->template
						 ->set_page_title('Register')
						 ->add_css_file('form.css')
						 ->set_view('register');
					$data['errors'] = validation_errors();
					
					$this->template->set_additional_data($data);	 
					$this->template->render();
			}

			}


		}

		public function new_member_ajax() {
			if ($this->input->is_ajax_request()) {

				if (!CI_Settings::get('Allow_New_Reg'))  {
					echo json_encode(array('status' => 0, 'msg' => "<p class=\"alert alert-danger\">" . lang('reg_closed') .  "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
					exit;
				}

				$this->load->library('form_validation');
					$this->validation_rules = array(	
						array(
							'field' => 'email',
							'label' => 'Email',
							'rules' => 'required|valid_email|is_unique[users.email]|max_length[32]|xss_clean' 
						), 
						array(
							'field' => 'password', 
							'label' => 'Password', 
							'rules' => 'required|min_length[5]'
						),
					);

					$this->form_validation->set_rules($this->validation_rules);

					if ($this->form_validation->run() == TRUE) {
						$this->load->helper('account'); 
						if (create_new_account($this->input->post('email'),$this->input->post('password'))) {
								if(get_user_status(get_username_byemail($this->input->post('email'))) == 1) {
									echo json_encode(array('status' => 'needactivation', 'msg' => "<p class=\"alert alert-info\">" .lang('activate_your_account'). "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
								}
								else {
									// Log the user
									$this->auth->login($this->input->post('email'),$this->input->post('password'));
									$data['is_user_logged_in'] = TRUE;
									$data['isAdmin'] = FALSE;
									$html = $this->load->view('fragment/main_nav_bar',$data,TRUE);
									echo json_encode(array('status' => 'registrationSuccess','loggedin' => 1, 'msg' => "<p class=\"alert alert-success\">" .  lang('account_created_and_connected') . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>",'html' => $html)); 
								}	
							}
							else 
							{
								echo json_encode(array('status' => 'registrationfail', 'msg' => "<p class=\"alert alert-danger\">". lang('error_when_creating_account') . "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></p>"));
							}
					}
					else {
						echo json_encode(array('status' => 0, 'msg' => validation_errors())); 
					}
			}	
		}

		
	}





?>