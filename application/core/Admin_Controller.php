<?php defined('BASEPATH') OR exit('No direct script access allowed');



 class Admin_Controller extends MY_Controller {

 	public function __construct() 
 	{
 		parent::__construct(); 
 	    $this->Check_Access();
 	}

 	public function Check_Access()
 	{
 		$is_logged_in = $this->session->userdata('logged_in');
 		$this->load->helper('url');

		if ((!isset($is_logged_in) || $is_logged_in != TRUE))
		{
			redirect('login');
		}

		if (isset($is_logged_in) && ! $this->session->userdata('Isadmin')) {
			redirect('/dashboard');
		}
 	}

 }






?>