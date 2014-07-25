<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class User extends Admin_Controller {

		protected $data;
		public function __construct() {

			parent::__construct();
			$this->load->helper('account');
			$this->load->model('suggestion_model');
			$this->load->model('users/user_model');
			$this->load->helper('util');
			
		}

		public function index() {

			if ($this->user_model->num_users_and_admins() == 0) {
				$this->data['empty'] = TRUE; 
			}
			else {
				$this->data['users'] = $this->user_model->get_users_and_admins(); 
			}

			$this->data['nav_selected'] = 'Users';
			$this->template->set_view('admin/user/index')
						   ->add_js_file('tablesorter/jquery.tablesorter.js')
						   ->add_js_file('tablesorter/tables.js')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css')
						   ->set_page_title('Users'); 


	 		$this->template->set_additional_data($this->data);
	 		$this->template->render();

		}

		public function edit($id = NULL) {
			$this->data['nav_selected'] = 'Users';
			$this->template->set_view('admin/user/edit')
						   ->set_page_title('Edit User')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');
			$this->load->library('users/auth');  
			$this->load->library('form_validation'); 

			if ($id) {
				$query = $this->user_model->get_where(array('ID' => $id)); 
				if ($query->num_rows() > 0) {
					$this->data['user'] = $query->row();
				}
				else
				{
					redirect('admin/user');
				}

				if ($this->user_model->is_admin_by_id($id)) {
					redirect('admin/user');
				}

			}
			else {
				$this->data['user'] = FALSE;
				$this->data['errors'][] = 'User could not be found'; 

			}

			$rules = array( 
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'trim|required|min_length[4]|max_length[50]|callback__unique_username[' . $id .']|'
				));

			if ($this->input->post('password')) {
				$rules[] = 
					array(
						  'field' => 'password',
						  'label' => 'Password',
						  'rules' => 'trim|required|min_length[4]|max_length[50]|matches[password_confirm]'
					);

			 	$rules[] = 
					array(
						  'field' => 'password_confirm',
						  'label' => 'Confirm Password',
						  'rules' => 'trim|required|matches[password]'
					);
			
			}

			
			$this->form_validation->set_rules($rules); 
			if ($this->form_validation->run() == TRUE) {
				$this->user_model->change_username($id,$this->input->post('username'));
				if ($this->input->post('rights')) {
					$this->user_model->set_admin_rights($id);
				} else {
					$this->user_model->remove_admin_rights($id);
				}

				if ($this->input->post('password')){
					$this->auth->update_password($id,$this->input->post('password'));
				}

				redirect('admin/user/edit/'.$id.'?saved=true');
			}


			$this->load->helper('form');
			$this->template->set_additional_data($this->data);
			$this->template->render();
			
		}

		public function _unique_username($str,$id) {
			if ($str == get_username_byId($id)) {
				return TRUE;
			}

			$query = $this->user_model->get_where(array('Username' =>  $str)); 
			if ($query->num_rows() > 0) {
				$this->form_validation->set_message('_unique_username', '%s should be unique');
				return FALSE;
			}

			return TRUE;
		}

		public function add() {
			$this->data['nav_selected'] = 'Users';
			$this->template->set_view('admin/user/edit')
						   ->set_page_title('Add User')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css');

			$this->load->library('users/auth');  
			$this->load->library('form_validation');

			$rules = $this->auth->rules_admin; 
			$this->form_validation->set_rules($rules); 
			if ($this->form_validation->run() == TRUE) {
				$user = array(
					'Username'  => $this->input->post('username'), 
					'Password'	=> $this->input->post('password'), 
					'email'	   	=> $this->input->post('email'), 
					'user_role' => $this->input->post('rights') ? 1 : 2
					);


				if ($this->auth->register($user)) {

					if ($this->input->post('sendemail')) {
						$this->load->library('notifier');
						$data = array('action' => "admin_added_user",'Username' => $this->input->post('username'),'email' => $this->input->post('email'),'password' => $this->input->post('password'));
						Events::trigger('notify',$data,'array');
					}
					redirect('admin/user');
				}

			}

			$this->template->set_additional_data($this->data);
			$this->template->render(); 
		}

		public function delete($id = NULL) {
			if ($id == NULL) 
				redirect('admin/user'); 

			remove_account($id);
			redirect('admin/user'); 
		}

		public function delete_group() {
			$ids = $this->input->post('ids');
			if (!$ids)
				echo json_encode(array('status' => 0));

			foreach ($ids as $id) {
				remove_account($id);
			}
			echo json_encode(array('status' => 1));
		}

		public function block_user($id) {
			if (!$id)
				redirect('/');
			$this->load->library('users/auth');
			$this->auth->block_user($id);
			redirect('admin/user/edit/'.$id.'?blocked=true');
		}

		public function unblock_user($id) {
			if (!$id)
				redirect('/'); 
			$this->load->library('users/auth'); 
			$this->auth->unblock_user($id);
			redirect('admin/user/edit/'.$id.'?unblocked=true');
		}

		public function activate($id) {
			if (!$id) 
				redirect('/'); 

			$this->load->library('users/auth'); 
			$this->auth->Activate($id); 
			redirect('admin/user/edit/'.$id.'?activated=true');
		}



		
	}
?>