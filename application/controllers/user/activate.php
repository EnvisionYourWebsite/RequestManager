<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


	class Activate extends Public_Controller {
		public function __construct() {
			parent::__construct();
			$this->load->helper('account'); 
			$this->load->model('users/user_model'); 
		}

		public function index() {
			redirect('/');
		}

		public function account() {
			$user = $this->uri->segment(4); 
			$code = $this->uri->segment(5); 

			if (!empty($user) && !empty($code)) {
				if ($this->user_model->record_exists(array('Username' => $user,'Activation_Code' => $code))) {
					// Success
					$id = $this->user_model->get_User_Id($user);
					$this->user_model->activate($id);
					redirect('home?activated=true');
				}

				redirect('/');
			}
		}
	}







?>