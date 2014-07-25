<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	class Dashboard extends AuthenticatedUser_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->helper('account'); 
			$this->load->model('suggestion_model');
		}

		public function index(){
			$this->template->set_page_title('Dashboard')
						   ->add_css_file('font-awesome/css/font-awesome.min.css')
						   ->add_css_file('sb-admin.css')
						   ->set_view('user/dashboard'); 

			if (is_admin_connected()) {
				$this->load->helper('url');
				redirect('admin/dashboard');
			}
			$this->data['nav_selected'] = 'Dashboard';

			if ($this->suggestion_model->isEmpty_where(array('UserID' =>  get_connected_user_id()))) {
				$this->data['empty'] = TRUE;
			}
			else 
			{
				$this->load->library('pagination'); 

				$config['base_url']   = site_url() . '/dashboard';
				$config['total_rows'] = $this->suggestion_model->num_rows_where(array('UserID' =>  get_connected_user_id())); 
				$config['per_page']	  = 10; 

				$this->pagination->initialize($config); 

				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0; 
				$this->data['results'] = $this->suggestion_model->fetch_where($config['per_page'],$page,array('UserID' => get_connected_user_id())); 
			}

			$this->template->set_additional_data($this->data);
			$this->template->render();
		}

		public function save_settings() {
			$this->load->model('settings/users/usersettings_model'); 
			$this->load->helper('account');
			$UserID = get_connected_user_id();
			if ($this->input->post('receive')) {
					$this->usersettings_model->insert_or_update(array('live_status' => 'TRUE'),$UserID);
			}
			else {
					$this->usersettings_model->insert_or_update(array('live_status' => 'FALSE'),$UserID);
					
			}

			redirect('dashboard?saved=true');
		}


		
		
		
	}





?>