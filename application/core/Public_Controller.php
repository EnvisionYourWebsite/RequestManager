<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Public_Controller  extends MY_Controller {


		public function __construct()
		{
			parent::__construct();

			$this->load->library('users/auth'); 
			$this->auth->is_logged_in();
			
		}




}


?>