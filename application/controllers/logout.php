<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

	
	class Logout extends AuthenticatedUser_Controller  {
		public function __construct() {
			parent::__construct(); 
			$this->load->library('users/auth'); 
			
		}

		public function index() {
			if ($this->auth->logout()) {
				$this->load->helper('url'); 
				redirect('login'); 
			}
		}

		
	}

?>